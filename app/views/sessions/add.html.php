<div class=row>
    <div class="col-md-3 col-md-offset-4">
    <?php if ($noauth): ?>
    <h4>Login failed</h4>
    <?php endif; ?>
    <?=$this->form->create(null); ?>
        <div class="form-group">
            <?=$this->form->field('username', array('type' => 'text', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?=$this->form->field('password', array('type' => 'password', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?=$this->form->submit('Log in', array('class' => 'btn btn-default')); ?>
        </div>
    <?=$this->form->end(); ?>
    </div>
</div>