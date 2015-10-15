
<?php if ($noauth): ?>
<h4>Login failed</h4>
<?php endif; ?>
<?=$this->form->create(null); ?>
    <?=$this->form->field('username'); ?>
    <?=$this->form->field('password', array('type' => 'password')); ?>
    <?=$this->form->submit('Log in'); ?>
<?=$this->form->end(); ?>