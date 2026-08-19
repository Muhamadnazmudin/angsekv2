<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_model extends CI_Model
{
    protected $table_point = 'upload_point';
    protected $table_file  = 'upload_berkas';

    public function get_points($keyword = null)
    {
        $this->db
            ->select('p.*')
            ->from($this->table_point . ' p')
            ->where('p.aktif', 1)
            ->order_by('p.nomor', 'ASC');

        if ($keyword !== null && $keyword !== '') {
            $this->db->group_start();
            $this->db->like('p.nama_point', $keyword);
            $this->db->or_like('p.nomor', $keyword);
            $this->db->group_end();
        }

        return $this->db->get()->result();
    }

    public function get_point($id)
    {
        return $this->db
            ->where('id', $id)
            ->get($this->table_point)
            ->row();
    }

    public function get_files_by_point($point_id, $tahun = null)
    {
        $this->db
            ->where('point_id', $point_id);

        if ($tahun !== null) {
            $this->db->where('tahun', $tahun);
        }

        return $this->db
            ->order_by('uploaded_at', 'DESC')
            ->get($this->table_file)
            ->result();
    }

    public function get_file($id)
    {
        return $this->db
            ->select('f.*, p.nomor, p.nama_point')
            ->from($this->table_file . ' f')
            ->join($this->table_point . ' p', 'p.id = f.point_id')
            ->where('f.id', $id)
            ->get()
            ->row();
    }

    public function insert_point($data)
    {
        return $this->db->insert($this->table_point, $data);
    }

    public function update_point($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table_point, $data);
    }

    public function delete_point($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table_point);
    }

    public function insert_file($data)
    {
        return $this->db->insert($this->table_file, $data);
    }

    public function delete_file($id)
    {
        return $this->db
            ->where('id', $id)
            ->delete($this->table_file);
    }

    public function get_stats()
    {
        $stats = array();

        foreach ([2025, 2026] as $tahun) {

            $stats[$tahun] = array(
                'total_point' => $this->db
                    ->where('aktif', 1)
                    ->count_all_results($this->table_point),

                'point_terisi' => $this->db
                    ->select('COUNT(DISTINCT point_id) AS total', false)
                    ->where('tahun', $tahun)
                    ->get($this->table_file)
                    ->row()
                    ->total,

                'total_file' => $this->db
                    ->where('tahun', $tahun)
                    ->count_all_results($this->table_file)
            );
        }

        return $stats;
    }

    public function count_files($point_id, $tahun)
    {
        return $this->db
            ->where('point_id', $point_id)
            ->where('tahun', $tahun)
            ->count_all_results($this->table_file);
    }
}