<br>
<?php $session = session();
          $nombre= $session->get('nombre');
          $perfil=$session->get('perfil_id');
          $id=$session->get('id');?>  
 <?php if($perfil == 1){  ?>
<div class="container mt-1 mb-0 nuevoTurno">
  <div class=""  >
    <div class= "card-header text-center">
      <h2>Editar Usuarios</h2>
    </div>
 <?php $validation = \Config\Services::validation(); ?>
     <form method="post" action="<?php echo base_url('/enviarEdicion') ?>">
      <?=csrf_field();?>
      <?php if(!empty (session()->getFlashdata('fail'))):?>
      <div class="alert alert-danger"><?=session()->getFlashdata('fail');?></div>
 <?php endif?>
           <?php if(!empty (session()->getFlashdata('success'))):?>
      <div class="alert alert-danger"><?=session()->getFlashdata('success');?></div>
  <?php endif?>     
<div class ="card-body" media="(max-width:768px)">
  <div class="mb-2">
   <label for="exampleFormControlInput1" class="form-label">Nombre</label>
   <input name="nombre" type="text"  class="form-control" placeholder="nombre" 
   value="<?php echo $data['nombre']?>" required minlength="3" maxlength="20">
     <!-- Error -->
        <?php if($validation->getError('nombre')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('nombre'); ?>
            </div>
        <?php }?>
  </div>
  <div class="mb-3">
   <label for="exampleFormControlTextarea1" class="form-label">Apellido</label>
    <input type="text" name="apellido" required class="form-control" placeholder="apellido" value="<?php echo $data['apellido'] ?>" minlength="3" maxlength="20">
    <!-- Error -->
        <?php if($validation->getError('apellido')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('apellido'); ?>
            </div>
        <?php }?>
    </div>
    <div class="mb-3">
       <label for="exampleFormControlInput1" class="form-label">Email</label>
   <input name="email"  type="email" class="form-control"  placeholder="correo@algo.com" value="<?php echo $data['email']?>" required="required">
    <!-- Error -->
        <?php if($validation->getError('email')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('email'); ?>
            </div>
        <?php }?>
  </div>
  
  <div class="mb-3">
       <label for="exampleFormControlInput1" class="form-label">Teléfono</label>
   <input name="telefono"  type="text" class="form-control"  placeholder="Telefono" value="<?php echo $data['telefono']?>"  maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
    <!-- Error -->
        <?php if($validation->getError('telefono')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('telefono'); ?>
            </div>
        <?php }?>
  </div>

  <div class="mb-3">
       <label for="exampleFormControlInput1" class="form-label">Direccion</label>
   <input name="direccion"  type="text" class="form-control"  placeholder="Direccion" value="<?php echo $data['direccion']?>" >
    <!-- Error -->
        <?php if($validation->getError('direccion')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('direccion'); ?>
            </div>
        <?php }?>
  </div>
  
  <div class="mb-3">
  <label for="exampleFormControlInput1" class="form-label">Pass</label>
   <input name="pass" type="text" class="form-control"  placeholder="password" value="<?php echo $data['pass']?>" minlength="3" maxlength="20"required="required">
   <!-- Error -->
        <?php if($validation->getError('pass')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('pass'); ?>
            </div>
        <?php }?>
  </div>
  <br>
  <div class="mb-3">
   <?php  
  $perfil='';
  switch ($data['perfil_id']) {
    case 1:
        $perfil = 'Admin';
        break;
    case 2:
        $perfil = 'Vendedor';
        break;
}?>
   <label for="exampleFormControlInput1" class="form-label">Tipo de Perfil</label>
   <select name="perfil_id">
       <?php if($data['id'] == 1){?>
    <option value="<?php echo $data['perfil_id']?>"><?php echo $perfil ?></option>
    
        <?php } else {?>
        <option value="<?php echo $data['perfil_id']?>"><?php echo $perfil ?></option>
        <option value="2">Vendedor</option>
        <option value="1">Admin</option>
        
        <?php } ?>
    </select>
   <!-- Error -->
        <?php if($validation->getError('perfil_id')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('perfil_id'); ?>
            </div>
        <?php }?>
  </div>

  <div class="mb-3">
   <label for="exampleFormControlInput1" class="form-label">Eliminado</label>
   <input name="baja" type="text" readonly="true" class="form-control"  placeholder="baja" value="<?php echo $data['baja']?>">
   <!-- Error -->
        <?php if($validation->getError('baja')) {?>
            <div class='alert alert-danger mt-2'>
              <?= $error = $validation->getError('baja'); ?>
            </div>
        <?php }?>
  </div>

  <input type="hidden" name="id" value="<?php echo $data['id']?>">

  <br>
  <div style="text-align: end;">
            <a type="reset" href="<?php echo base_url('usuarios-list');?>" class="btn" style="text-align: end;">Cancelar</a>
            <input type="submit" value="Editar" class="btn" >
  </div>

 </div>
</form>
<?php }else{ ?>
  <h2>Su perfil no tiene acceso a esta parte,
    Vuelva a alguna seccion de Empleado!
  </h2>
<?php }?>
</div>
</div>
<br>