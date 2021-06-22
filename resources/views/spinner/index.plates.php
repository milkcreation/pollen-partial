<?php
/**
 * @var Pollen\Partial\PartialTemplate $this
 */
?>
<?php $this->before(); ?>
<div <?php $this->attrs(); ?>>
    <?php
        if ($spinner = $this->fetch($this->get('spinner'))) :
            echo $spinner;
        else :
            $this->insert('spinner-pulse');
        endif;
    ?>
</div>
<?php $this->after();