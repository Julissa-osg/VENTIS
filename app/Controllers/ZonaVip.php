<?php
namespace App\Controllers;

use App\Models\TiendaModel;

class ZonaVip extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Verificar estado actual
        $tiendaModel = new TiendaModel();
        $tienda = $tiendaModel->find(session()->get('id_tienda'));

        return view('tienda/vip/canjear', ['tienda' => $tienda]);
    }

    public function canjear()
    {
        $codigo = $this->request->getPost('codigo');
        $idTienda = session()->get('id_tienda');

        if (!$codigo) return redirect()->back()->with('error', 'Escribe un código.');

        // 1. Buscar código válido en la tabla codigos_vip
        $codigoRow = $this->db->table('codigos_vip')
                              ->where('codigo', $codigo)
                              ->where('estado', 'Activo') // Solo códigos sin usar
                              ->get()->getRowArray();

        if (!$codigoRow) {
            return redirect()->back()->with('error', 'Código inválido o ya usado.');
        }

        // 2. Transacción para aplicar el VIP
        $this->db->transStart();

        // A. Actualizar Tienda
        $this->db->table('tiendas')->where('id', $idTienda)->update([
            'estado_vip' => 1,
            'fecha_expiracion_vip' => date('Y-m-d H:i:s', strtotime("+" . $codigoRow['duracion_dias'] . " days"))
        ]);

        // B. Quemar el código (Marcar como usado)
        $this->db->table('codigos_vip')->where('id', $codigoRow['id'])->update([
            'estado' => 'Usado',
            'id_tienda_uso' => $idTienda,
            'fecha_uso' => date('Y-m-d H:i:s')
        ]);

        // C. Actualizar Sesión (Importante para que el usuario vea el cambio ya)
        session()->set('is_vip', true);

        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            return redirect()->back()->with('error', 'Error al procesar el código.');
        }

        return redirect()->to(site_url('tienda/dashboard'))->with('success', '¡Felicidades! Ahora eres VIP por ' . $codigoRow['duracion_dias'] . ' días.');
    }
}