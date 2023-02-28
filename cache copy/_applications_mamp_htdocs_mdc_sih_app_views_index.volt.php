<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?= $this->tag->getTitle() ?>s</title>


        

        <?= $this->assets->outputCss('target_final_css') ?> 
        <?= $this->assets->outputJs('target_final_js') ?>

        <meta name="author" content="Target Team">
        <link rel="icon" type="image/png" href="<?= $this->url->get('img/target.png') ?>" />
    </head>
        <?= $this->getContent() ?>

</html>
