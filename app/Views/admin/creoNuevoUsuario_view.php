<br>
<div class="nuevoTurno">
  <div class="" style="width: 100%;" >
    <div class= "">
      <h2>Nuevo Usuario / Vendedor</h2>
    </div>
  
 <?php $validation = \Config\Services::validation(); ?>
     <form method="post" action="<?php echo base_url('crearUs') ?>">
      <?=csrf_field();?>
      <?php if(!empty (session()->getFlashdata('fail'))):?>
      <div class="alert alert-danger"><?=session()->getFlashdata('fail');?></div>
 <?php endif?>
           <?php if(!empty (session()->getFlashdata('success'))):?>
      <div class="alert alert-danger"><?=session()->getFlashdata('success');?></div>
  <?php endif?>     
<div class ="" media="(max-width:768px)">
  <div class="">
   <label for="exampleFormControlInput1" class="form-label">Nombre</label>
   <input name="nombre" type="text"  class="form-control" placeholder="nombre" required minlength="3" maxlength="20">
     <!-- Error -->
        <?php if($validation->getError('nombre')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('nombre'); ?>
            </div>
        <?php }?>
  </div>
  <div class="">
   <label for="exampleFormControlTextarea1" class="">Apellido</label>
    <input type="text" name="apellido"class="" placeholder="apellido" required minlength="3" maxlength="20" >
    <!-- Error -->
        <?php if($validation->getError('apellido')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('apellido'); ?>
            </div>
        <?php }?>
    </div>
    <div class="">
       <label for="exampleFormControlInput1" class="">Email</label>
       <input name="email"  type="email" class="form-control"  placeholder="correo@algo.com" required  maxlength="50">
       <!-- Error -->
        <?php if($validation->getError('email')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('email'); ?>
            </div>
        <?php }?>
  </div>
  
  <div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Telefono</label>
   <input  type="text" name="telefono" class="form-control" placeholder="Telefono" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
   <!-- Error -->
        <?php if($validation->getError('telefono')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('telefono'); ?>
            </div>
        <?php }?>
  </div>

  <div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Direccion</label>
   <input  type="text" name="direccion" class="form-control" placeholder="Direccion" maxlength="100">
   <!-- Error -->
        <?php if($validation->getError('direccion')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('direccion'); ?>
            </div>
        <?php }?>
  </div>

  <div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Contraseña:</label>

   <input name="pass" type="text" class=""  placeholder="password" value="" minlength="3" maxlength="20"required="required">
   <!-- Error -->
        <?php if($validation->getError('pass')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('pass'); ?>
            </div>
        <?php }?>
  </div>
  <br>
  <div class="mb-3">
   <label for="exampleFormControlInput1" class="form-label">Perfil:</label>
   <select name="perfil_id">
    <option>Seleccione Perfil</option>
    <option value="2">Vendedor</option>
    <option value="1">Admin</option>
    </select>
   <!-- Error -->
        <?php if($validation->getError('perfil_id')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('perfil_id'); ?>
            </div>
        <?php }?>
  </div>
  <br>
  <div class="button-container">
          <a type="reset" href="<?php echo base_url('usuarios-list');?>" class="button2">Cancelar</a>
          <button type="submit" class="button2">Crear US</button>
      <br>
        </div>
 </div>
</form>
</div>
</div>
<br>