<h1>Gestión de Roles</h1>
<section class="WWFilter">

</section>
<section class="WWList table-responsive">
  <table class="table table-striped table-hover tb-align">
    <thead>
      <tr class="bg-gris_oscuro tb-align text-white p-5">        
        <th scope="col">Código</th>
        <th scope="col">Descripción</th>
        <th scope="col">Estado</th>   
        <th>
          {{if new_enabled}}
          <button class="bg-dark rounded" id="btnAdd"><i class="fa-solid fa-plus" style="color: #ffffff;"></i></button>
          {{endif new_enabled}}
        </th>
      </tr>
    </thead>
    <tbody>
      {{foreach roles}}
      <tr class="bg-white">
        <td>{{rolescod}}</td>
        <td><a href="index.php?page=mnt_rol&mode=DSP&rolescod={{rolescod}}">{{rolesdsc}}</a></td>
        <td>{{rolesest}}</td>

        <td>
          {{if ~edit_enabled}}
          <form action="index.php" method="get">
             <input type="hidden" name="page" value="mnt_rol"/>
              <input type="hidden" name="mode" value="UPD" />
              <input type="hidden" name="rolescod" value={{rolescod}} />
              <button type="submit" class="bg-primary"><i class="fa-solid fa-pen-to-square fa-lg"></i></button>
          </form>
          {{endif ~edit_enabled}} <br>
          {{if ~delete_enabled}}
          <form action="index.php" method="get">
             <input type="hidden" name="page" value="mnt_rol"/>
              <input type="hidden" name="mode" value="DEL" />
              <input type="hidden" name="rolescod" value={{rolescod}} />
              <button type="submit" class="bg-danger"><i class="fa-solid fa-trash fa-lg"></i></button>
          </form>
          {{endif ~delete_enabled}}
        </td>
      </tr>
      {{endfor roles}}
    </tbody>
  </table>
</section>

<script>
   document.addEventListener("DOMContentLoaded", function () {
      document.getElementById("btnAdd").addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        window.location.assign("index.php?page=mnt_rol&mode=INS&rolescod=0");
      });
    });
</script>