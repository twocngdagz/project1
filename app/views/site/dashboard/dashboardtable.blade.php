<table class="table table-bordered table-striped" style="text-align:center">
    <?php  $t = count($data1);
    if($t>0){
        ?>
        @foreach($headers as $headercopy)
        <th style="text-align:center">{{ $headercopy }}</th>
        @endforeach
    <?php }else{ ?>
        <h3>Table is empty. Please, select values ​​in the filters.</h3>
    <?php }?>
    <?php
    $t = count($data1);
    for ($i = 0; $i < $t; $i++)
    {
        echo '<tr>';
        array_walk_recursive($data1[$i], function ($item, $key) {
            echo "<td> $item </td>";
        });
        echo '</tr>';
    }
    ?>
</table>