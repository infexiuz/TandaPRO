<?php require('header.php');?>
<div class="page">
   <h1>Formularios</h1>
   <div class="row">
      <div class="col-lg-6">
         <div class="card">
            <form>
               <h2>Formulario de contacto</h2>
               <div class="form-group">
                  <input type="text" id="nombre" name="nombre" required placeholder=" ">
                  <label for="nombre">Nombre</label>
               </div>
               <div class="form-group">
                  <div class="select">
                     <select class="select-text" required>
                        <option value="" disabled selected></option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                     </select>
                     <span class="select-highlight"></span>
                     <span class="select-bar"></span>
                     <label class="select-label">Selecciona una opción</label>
                  </div>
               </div>
               <div class="form-group">
                  <input type="email" id="email" name="email" required placeholder=" " autocomplete="off">
                  <label for="email">Correo electrónico</label>
               </div>
               <div class="form-textarea">
                  <textarea placeholder=" " name="description"></textarea>
                  <label for="textarea">Escribe la descripción del post</label>
               </div>
               <div class="form-group">
                  <input type="password" id="password" name="password" required placeholder=" ">
                  <label for="password">Contraseña</label>
               </div>
            </form>
            <div class="d-block">
               <div class="dropzone">
                  <form class="content-area">
                     <input class="file-input" type="file" name="file" hidden accept="image/*">
                     <img src="" class="mt-4" id="img-dragzone" width="200px">
                     <i class="fas fa-cloud-upload-alt" id="icon-dragzone"></i>
                     <p>Arrastra o selecciona un archivo</p>
                  </form>
                  <section class="progress-area"></section>
                  <section class="uploaded-area"></section>
               </div>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
            <button type="button" class="btn">Cancelar</button>
         </div>
      </div>
      <div class="col-lg-6">
         <div class="card">
            <div class="form-wizard-wrapper">
               <ul>
                  <li><a class="form-wizard-link active" href="javascript:;"
                        data-attr="info"><span>Información</span></a></li>
                  <li><a class="form-wizard-link" href="javascript:;" data-attr="img"><span>Imagen</span></a></li>
                  <li class="form-wizardmove-button"></li>
               </ul>
               <div class="form-wizard-content-wrapper">
                  <div class="form-wizard-content show mt-4" data-tab-content="info">
                     <h2>Información</h2>
                     <div class="form">
                        <div class="form-group">
                           <input type="text" name="title" required placeholder=" ">
                           <label for="title">Titulo</label>
                        </div>
                        <div class="form-group">
                           <div class="select">
                              <select class="select-text" name="category " required>
                                 <option value="" disabled selected></option>
                                 <option value="1">Option 1</option>
                                 <option value="2">Option 2</option>
                                 <option value="3">Option 3</option>
                              </select>
                              <span class="select-highlight"></span>
                              <span class="select-bar"></span>
                              <label class="select-label">Selecciona una categoría</label>
                           </div>
                        </div>
                        <div class="form-textarea">
                           <textarea placeholder=" " name="description"></textarea>
                           <label for="textarea">Escribe la descripción del post</label>
                        </div>
                        <div class="d-block">
                           <textarea id="summernote" name="editordata"></textarea>
                        </div>
                        <div class="full-wdth clearfix">
                           <a href="javascript:;" class="form-wizard-next-btn float-right btn">Next</a>
                        </div>
                     </div>
                  </div>
                  <div class="form-wizard-content mt-4" data-tab-content="img">
                     <h2>Imagen</h2>
                     <div class="form">
                        <div class="form-group">
                           <input type="text" placeholder=" ">
                           <label for="">First Name</label>
                        </div>
                        <div class="form-group">
                           <input type="text" placeholder=" ">
                           <label for="">Last Name</label>
                        </div>
                        <div class="full-wdth clearfix">
                           <a href="javascript:;" class="form-wizard-previous-btn float-left btn">Previous</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php require('footer.php');?>