<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>


<input disabled type="hidden" id="recNum" value="<?php echo $rec ?>"/>
<table cellspacing="1" cellpadding="1" class="sample1">
    <thead>
        <tr>
            <th width="50">No</th>
            <th width="50">PLU</th>
            <th width="500">NAMA BARANG</th>
            <th width="100">SATUAN</th>
            <th style="text-align: right" width="100">HARGA</th>
            <th colspan="2" style="text-align: center">ACTION</th>
        </tr>
    </thead>
    <tbody>
<?php foreach ($query as $qry => $row): ?>
            <tr>
                <td><?php echo ($qry + 1) + $offset; ?></td>
            <td><?php echo $row['plu']; ?></td>
            <td><?php echo $row['descp']; ?></td>
            <td><?php echo $row['satuan']; ?></td>
            <td style="text-align: right"><?php echo rupiah_format($row['harga']); ?></td>
            <td style="text-align: center"><a href="" id="<?php echo  $row['plu'] ?>" name="btnUbah">ubah</a></td>
            <td style="text-align: center" ><a href="" id="<?php echo  $row['plu'].'-'.$row['descp']; ?>" name="btnHapus">hapus</a></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>