<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagu_model extends CI_Model {

    public function get_all()
    {
        return $this->db->order_by('tahun', 'DESC')
                        ->get('pagu_anggaran')
                        ->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('pagu_anggaran', ['id' => $id])->row();
    }

    public function get_by_tahun($tahun)
    {
        return $this->db->get_where('pagu_anggaran', ['tahun' => $tahun])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('pagu_anggaran', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)
                        ->update('pagu_anggaran', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('pagu_anggaran', ['id' => $id]);
    }
}
