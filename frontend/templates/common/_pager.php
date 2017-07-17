<?php
function buildPaginationUrl($url, $page){
    $data = parse_url($url);

    if(isset($data['query'])){
        parse_str($data['query'], $args);
    } else {
        $args = array(
            'sort_by'   => 'matching_percentage',
            'order_by'  => 'desc',
        );
    }

    return $data['path'] . '?' . http_build_query(array_merge($args, compact('page')));
}
?>
<?php if($pages > 1): ?>
<ul class="pagination">
    <?php if($page > 1): ?>
        <li><a href="<?php echo buildPaginationUrl($_SERVER['REQUEST_URI'], $page-1) ?>" data-page="<?php echo $page - 1; ?>">&laquo;</a></li>
    <?php endif; ?>

    <?php for($i = 1; $i <= $pages; $i++): ?>
        <li<?php if($page == $i) echo " class='active' " ?>><a href="<?php echo buildPaginationUrl($_SERVER['REQUEST_URI'], $i) ?>" data-page="<?php echo $i; ?>"><?php echo $i ?></a></li>
    <?php endfor; ?>

    <?php if($page < $pages): ?>
        <li><a href="<?php echo buildPaginationUrl($_SERVER['REQUEST_URI'], $page+1) ?>" data-page="<?php echo $page + 1; ?>">&raquo;</a></li>
    <?php endif; ?>
</ul>
<?php endif; ?>