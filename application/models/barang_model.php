<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * barang_model
 *
 * Created on Feb 21, 2011, 10:57:01 PM
 */

/**
 *
 * @author agung
 */
class Barang_model extends CI_Model {

    public $limit;
    public $offset;

    function __construct() {
        parent::__construct();
    }

    public function listBarang() {
        $query = $this->db->limit($this->limit, $this->offset)
                        ->get('barang');
        return $query->result_array();
    }

    public function listBarang_page($descp,$satuan) {
        $query = $this->db->select('plu,descp,satuan,harga')
                        ->from('barang')
                        ->like('descp', $descp)
                        ->like('satuan', $satuan)
                        ->get('', $this->limit, $this->offset);
        return $query->result_array();
    }

     public function numRec() {
        $result = $this->db->from('barang');
        return $result->count_all();
    }

    public function numRec_page($descp,$satuan) {
        $result = $this->db->like('descp', $descp)
                        ->like('satuan', $satuan)
                        ->from('barang')
                        ->count_all_results();
        return $result;
    }


    //CRUD FUNCTION

    public function selectBarang($plu) {
        $query = $this->db->where('plu', $plu)
            ->get('barang');

        return $query->result();
    }

    public function cekPLU($plu) {
        $query = $this->db->select('plu')
            ->where('plu', $plu)
            ->from('barang')
            ->count_all_results();
        return $query;
    }

    function addBarang($arrBarang) {
        $query = $this->db->insert('barang', $arrBarang);
        return $query;
    }

    function updtBarang($plu,$arrBarang) {
        $query = $this->db->where('plu', $plu)
            ->update('barang', $arrBarang);
        return $query;
    }


    function deleteBarang($plu) {
        $this->db->trans_begin();
        $this->db->query('DELETE FROM barang WHERE (barang.plu = "'.$plu.'")');
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            $result = 0;
        }
        else
        {
            $this->db->trans_commit();
            $result =1;
        }
        return $result;
    }

    //END CRUD FUNCTION
}

/* End of file barang_model */