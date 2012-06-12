<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Codeigniter 2 live searching ajax paging dengan jquery easyui</title>
<link href="<?php echo base_url();?>assets/css/styles.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/easyui/themes/default/easyui.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/easyui/themes/icon.css"/>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery-1.5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript">
    var hn = '<?php echo base_url();?>';

    function key_barang() {
        $('a#btnSimpan').hide();
        $('a#btnBatal').hide();
        $('a#btnTambah').show();
        $('input#search').keyup(
            function () {
                var msg = $('#search').val();
                var stn = $('select#select-satuan').val();
                $.post(hn + 'barang_ctl/search_barang', {descp_msg:msg, descp_satuan:stn}, function (data) {
                    $("#content_barang").html(data);
                    $('#pp').pagination({
                        pageNumber:1,
                        total:$('#recNum').val(),
                        //tentukan banyak rec yg mau ditampilkan disini
                        pageList:[20],
                        //sembunyikan pagelist pagintion easyui
                        showPageList:false
                    });
                });
            }).keyup();

    }
    $(document).ready(function () {
        key_barang();
    });


    $(function () {
        $('#pp').pagination({
            total:$('#recNum').val(),
            pageList:[20],
            showPageList:true,
            onSelectPage:function (pageNumber, pageSize) {
                $('#pp').pagination({loading:true});
                var msg = $('#search').val();
                var stn = $('select#select-satuan').val();
                $.post(hn + 'barang_ctl/search_barang/ #content_barang', {descp_msg:msg, descp_satuan:stn, pageNumber:pageNumber}, function (data) {
                    $("#content_barang").html(data);
                });
                $('#pp').pagination({loading:false});
            }
        });

        $("select#select-satuan").change(function () {
            var msg = $('#search').val();
            var stn = $('select#select-satuan').val();
            $.post(hn + 'barang_ctl/search_barang', {descp_msg:msg, descp_satuan:stn}, function (data) {
                $("#content_barang").html(data);
                $('#pp').pagination({
                    pageNumber:1,
                    total:$('#recNum').val(),
                    //tentukan banyak rec yg mau ditampilkan disini
                    pageList:[20],
                    //sembunyikan pagelist pagintion easyui
                    showPageList:false
                });
            });
        });
    });
</script>
</head>
<body>
<div class="sample">
    <div style="width: 950px;">
        <div style="float: left;">
            <label for="search">Cari Barang</label>
            <input type="text" name="search" id="search"/>
            <label for="select-satuan">Satuan</label>
            <select id="select-satuan">
                <option value="">--saring satuan--</option>
                <option value="BKS">BKS</option>
                <option value="BH">BH</option>
                <option value="PCS">PCS</option>
                <option value="ROLL">ROLL</option>
            </select>
        </div>
        <div style="float: right;">
            <a id="btnTambah" href="">Tambah</a>
            <a id="btnSimpan" href="">Simpan</a>
            <a id="btnBatal" href="">Batal</a>
        </div>
    </div>
    <div style="clear: both;"></div>
    <div id="Alert"></div>
    <div id="content_barang"></div>
    <div id="pp"></div>
</div>

<script type="text/javascript">
    //Tambah - Add
    $('a#btnTambah').live('click', function (e) {
        e.preventDefault();
        $("#Alert").slideUp(200);
        $.ajax({
            type:'POST',
            url:'<?php echo base_url();?>barang_ctl/addBarang',
            data:'id=add',
            success:function (msg) {
                $('#search').attr('disabled', 'disabled');
                $('#select-satuan').attr('disabled', 'disabled');
                $('div#content_barang').hide();
                $('div#content_barang').empty();
                $('#pp').hide();
                $('a#btnTambah').hide();
                $('div#content_barang').html(msg);
                $('a#btnSimpan').show();
                $('a#btnBatal').show();
                $('div#content_barang').fadeIn(200);
            },
            error:function (xhr, ajaxOptions, thrownError) {
                $("#Alert").html("<p><strong><a style='color:red'>Ma'af </a></strong> Ada kesalahan!</p>");
                $('#Alert').slideDown(300);
            }
        });
        return false;
    });

    //BATAL - Cancel
    $('a#btnBatal').live('click', function (e) {
        e.preventDefault();
        key_barang();

        $('#pp').show();
        $('#search').removeAttr('disabled');
        $('#select-satuan').removeAttr('disabled');
        $('#Alert').slideUp(300);
    });


    //Simpan - save
    $('a#btnSimpan').live('click', function (e) {
        e.preventDefault();
        var vplu = $('#plu').val();
        var vnama = $('#namabarang').val();
        var vsatuan = $('#satuan').val();
        var vharga = $('#harga').val();
        var type = $('#hide_type').val();

        $.post(hn + 'barang_ctl/barang_exec', {
            type:type,
            plu:vplu,
            descp:vnama,
            satuan:vsatuan,
            harga:vharga
        }, function (data) {
            var x = eval('(' + data + ')');
            if (x.success == 1) {
                $('#content_barang').hide();
                try {
                    key_barang();
                    $('div#content_barang').fadeIn(200);
                    $('#search').removeAttr('disabled');
                    $('#select-satuan').removeAttr('disabled');
                    $("#Alert").html("<p><strong><a style='color:blue'>Success : </a></strong>  Barang '" + vnama + "' berhasil di Update.</p>");
                    $('#Alert').slideDown(300);
                    $('#pp').fadeIn(250);
                } catch (err) {
                }
            } else if (x.success == 2) {

                $("#Alert").html("<p><strong><a style='color:red'>Pemberitahuan : </a></strong> PLU Barang sudah ada, silahkan diulang kembali!</p>");
                $('#Alert').slideDown(300);
                $('input#plu').focus().select();
            }
            else if (x.success == 3) {

                $("#Alert").html(x.error);
                $('#Alert').slideDown(300);
                $('input#plu').focus().select();
            }
        })

            .error(function () {
                $('#search').removeAttr('disabled');
                $('#Alert').removeAttr('class');
                $("#Alert").addClass("nNote nFailure hideit");
                $("#Alert").html("<p><strong><a style='color:red'>Pemberitahuan : </a></strong> terjadi kesalahan, silahkan diulang kembali!</p>");
                $('#Alert').slideDown(300);
            });
        return false;
    });


    //UBAH - update
    $('a[name=btnUbah]').live('click', function (e) {
        e.preventDefault();
        var vplu = $(this).attr('id');
        $("#Alert").slideUp(200);
        $.ajax({
            type:'POST',
            url:hn + 'barang_ctl/uptbarang',
            data:'plu=' + vplu,
            success:function (msg) {
                $('div#content_barang').fadeOut(200);
                $('div#content_barang').empty();
                $('#pp').hide();
                $('a#btnTambah').hide();
                $('#content_barang').html(msg);
                $('a#btnSimpan').show();
                $('a#btnBatal').show();
                $('#search').attr('disabled', 'disabled');
                $('#select-satuan').attr('disabled', 'disabled');
                $('div#content_barang').fadeIn(200);
            },
            error:function (xhr, ajaxOptions, thrownError) {

                $("#Alert").html("<p><strong><a style='color:red'>Ma'af </a></strong> Ada kesalahan!</p>");
                $('#Alert').slideDown(300);
            }
        });
        return false;
    });

    //HAPUS - delete
    $('a[name=btnHapus]').live('click', function (e) {
        e.preventDefault();
        var plu = $(this).attr('id');
        var parent = $(this).parent('td').parent('tr');
        var $hasil = plu.split("-");
        var vbarang = $hasil[1];
        $('#Alert').hide();
        $.messager.confirm('Hapus Barang', 'Hapus Nama Barang "' + vbarang + '" ?', function (r) {
            if (r) {

                $.ajax({
                    type:'POST',
                    url:hn + 'barang_ctl/hapusbarang_exec',
                    data:'plu=' + $hasil[0],
                    success:function (msg) {
                        var x = eval('(' + msg + ')');
                        if (x.success == 1) {
                            parent.slideUp(300, function () {
                                parent.remove();
                            });

                            $("#Alert").html("<p><strong><a style='color:blue'>Success : </a></strong>  Barang <a style='color:green'>' " + vbarang + " '</a> Berhasil di Hapus.</p>");
                            $('#Alert').slideDown(300);
                        }
                        else if ((x.success == 2)) {
                            $("#Alert").html("<p><strong><a style='color:maroon'>Pemberitahuan :</a></strong> Barang <a style='color:green'>' " + vbarang + " '</a> Tidak dapat diHapus!</p>");
                            $('#Alert').slideDown(300);
                        }
                        else {

                            $("#Alert").html("<p><strong><a style='color:red'>Pemberitahuan : </a></strong> terjadi kesalahan, silahkan diulang kembali!</p>");
                            $('#Alert').slideDown(300);
                        }
                    },
                    error:function (xhr, ajaxOptions, thrownError) {

                        $("#Alert").html("<p><strong><a style='color:red'>Ma'af </a></strong> Ada Kesalahan!</p>");
                        $('#Alert').slideDown(300);

                    }
                });
                return false;
            }
        });

    });
</script>
</body>
</html> 