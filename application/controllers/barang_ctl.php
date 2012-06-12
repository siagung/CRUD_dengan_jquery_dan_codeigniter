<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * barang_ctl
 *
 * Created on Mar 19, 2011, 10:41:15 AM
 */

/**
 *
 * @author agung
 */

class Barang_ctl extends CI_Controller {
    private $pagesize = 20;
    private $rules = array(
        array(
            'field' => 'plu',
            'label' => 'PLU',
            'rules' => 'required|trim|xss_clean|min_length[5]|max_length[5]|is_natural',
        ),
        array(
            'field' => 'descp',
            'label' => 'Nama Barang',
            'rules' => 'required|trim|xss_clean|min_length[3]',
        ),
        array(
            'field' => 'satuan',
            'label' => 'Satuan',
            'rules' => 'trim|xss_clean',
        ),
        array(
            'field' => 'harga',
            'label' => 'Harga',
            'rules' => 'trim|xss_clean|min_length[1]|is_natural',
        )
    );


	function __construct()
	{
		parent::__construct();
                $this->load->database();
		$this->load->model('barang_model');
                $this->load->helper(array('url','form','rupiah'));
	}

	 function index() {
        $this->barang_model->limit = 5;
        $this->barang_model->offset = $this->uri->segment(3);

        $result['query'] = $this->barang_model->listBarang();
        $result['numrec'] = $this->barang_model->numRec();

        $this->load->view('barang_view', $result);
    }

    function search_barang() {
        if (isset($_POST['descp_msg']) && $_POST['descp_msg'] != NULL) {
            $vDescp = $_POST['descp_msg'];
        }
        else
            $vDescp = '';

        if (isset($_POST['descp_satuan']) && $_POST['descp_satuan'] != NULL) {
            $vSatuan = $_POST['descp_satuan'];
        }
        else
            $vSatuan = '';

        if (isset($_POST['pageNumber']) && $_POST['pageNumber'] != NULL) {
            $idoffset = $_POST['pageNumber'] - 1;
        }
        else
            $idoffset=0;

        $this->barang_model->limit =  $this->pagesize;
        $this->barang_model->offset = $idoffset *  $this->pagesize;
        $result['offset'] = $this->barang_model->offset;
        $result['rec'] = $this->barang_model->numRec_page($vDescp,$vSatuan);
        $result['query'] = $this->barang_model->listBarang_page($vDescp,$vSatuan);

        $this->load->view('barang_view_page', $result);
    }


    ///CRUD FUNCTION
    function addBarang(){
        $result['type'] = 'insert';
        $result['query']['plu'] = '';
        $result['query']['descp'] = '';
        $result['query']['satuan'] = '';
        $result['query']['harga'] = '';
        $this->load->view('barang_form_page',$result);
    }


    function barang_exec()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $data['success'] = 0;
        $_type = $_POST['type'];
        $_plu = $_POST['plu'];
        $_descp = $_POST['descp'];
        $_satuan = $_POST['satuan'];
        $_harga = $_POST['harga'];
        if ($this->form_validation->run() == FALSE)
        {
            $data['success'] = 3;
            $data['error'] = validation_errors();
            echo json_encode($data);
        }else{
        if ($_type == 'insert') {
            $qry = $this->barang_model->cekPlu($_plu);
            if ($qry > 0) {
                $data['success'] = 2;
            } else {
                $arrBarangi = array('plu' => $_plu, 'descp' => $_descp, 'satuan' => $_satuan, 'harga' => $_harga);
                $result = $this->barang_model->addBarang($arrBarangi);
                if ($result) {
                    $data['success'] = 1;
                }
            }
        }
        else {
            $arrBarangu = array('descp' => $_descp, 'satuan' => $_satuan, 'harga' => $_harga);
            $result = $this->barang_model->updtBarang($_plu, $arrBarangu);
            if ($result) {
                $data['success'] = 1;
            }
        }
        echo json_encode($data);
        }
    }


    function uptbarang() {
        $_plu = $_POST['plu'];
        $qry=$this->barang_model->selectBarang($_plu);
        if ($qry):
            $result['type'] = 'update';
            foreach ($qry as $row) :
                $result['query']['plu'] = $row->plu;
                $result['query']['descp'] = $row->descp;
                $result['query']['satuan'] = $row->satuan;
                $result['query']['harga'] = $row->harga;
            endforeach;
            $result['pagesize'] = $this->pagesize;
            $this->load->view('barang_form_page',$result);
        else:
            echo '{status:0}';
        endif;
    }

    function hapusbarang_exec(){
        //$this->load->model('nasabah/nasabah_model');
        $_plu = $_POST['plu'];
        $data = 0;
        //$this->nasabah_model->cekNasabah_bank($_bankid);
        $result['data']=$data;
        if ($data == 0):
            $result['success'] =   $this->barang_model->deleteBarang($_plu);
        else:
            $result['success'] =2;
        endif;
        echo json_encode($result);
    }

}

/* End of file barang_ctl */