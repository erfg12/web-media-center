<div style="margin-left:auto;margin-right:auto;width:850px;position:relative;">

<form method="post">
<div class="adminPanels" id="admin" style="float:left;width:600px;">
<div class="panel panel-primary" style="min-width:600px;">
  <div class="panel-heading">
    <h3 class="panel-title">General Settings</h3>
  </div>
  <div class="panel-body">
<div class="input-group" title="website title"><span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span><input type="text" name="title" class="form-control" placeholder="Website Title" value="ADMIN_TITLE"></div>
<div class="input-group" style="margin-top:10px;" title="front page display quantity"><span class="input-group-addon"><span class="glyphicon glyphicon-list"></span></span><input name="fp_display" type="text" class="form-control" placeholder="Page Display Items" value="ADMIN_FPDISPLAY"></div>
<div class="input-group" style="margin-top:10px;" title="language"><span class="input-group-addon"><span class="glyphicon glyphicon-headphones"></span></span><select name="language" type="text" class="form-control" placeholder="Languages">
<!-- LANGOPT_BEGIN -->
LANGUAGE_OPTIONS
<!-- LANGOPT_END -->
</select></div>
<div class="input-group" style="margin-top:10px;" title="template"><span class="input-group-addon"><span class="glyphicon glyphicon-file"></span></span><select name="template" type="text" class="form-control" placeholder="Template">
<!-- TEMPOPT_BEGIN -->
TEMPLATE_OPTIONS
<!-- TEMPOPT_END -->
</select></div>
<div class="input-group" style="margin-top:10px;" title="video paths"><span class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></span><textarea name="video_paths" class="form-control" placeholder="directories" style="height:80px;">ADMIN_DIRECTORIES</textarea></div>
<div class="input-group" style="margin-top:10px;" title="EULA"><span class="input-group-addon"><span class="glyphicon glyphicon-inbox"></span></span><textarea name="eula" class="form-control" placeholder="eula" style="height:80px;">TEMP_EULA</textarea></div>
  </div>
</div>
</div>

<div class="adminPanels" style="float:left;width:600px;">
<div class="panel panel-primary" style="min-width:600px;">
  <div class="panel-heading">
    <h3 class="panel-title">TMDb Settings</h3>
  </div>
  <div class="panel-body">
<div class="input-group" title="TMDb API key"><span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span><input name="tmdb_key" type="text" class="form-control" placeholder="TMDb API key" value="ADMIN_TMDBKEY"></div>
  </div>
</div>
</div>

<div class="adminPanels" style="float:left;width:600px;">
<div class="panel panel-primary" style="min-width:600px;">
  <div class="panel-heading">
    <h3 class="panel-title">PayPal API Settings</h3>
  </div>
  <div class="panel-body">
<div class="input-group" title="PayPal API Username"><span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span><input name="API_UserName" type="text" class="form-control" placeholder="PayPal API Username" value="ADMIN_PAYPAL_USER"></div>
  <div class="input-group" style="margin-top:10px;" title="PayPal API Password"><span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span><input name="API_Password" type="text" class="form-control" placeholder="PayPal API Password" value="ADMIN_PAYPAL_PASS"></div>
  <div class="input-group" style="margin-top:10px;" title="PayPal API Signature"><span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span><input name="API_Signature" type="text" class="form-control" placeholder="PayPal API Signature" value="ADMIN_PAYPAL_SIG"></div>
  </div>
</div>
</div>

<div class="adminPanels" style="position:absolute;right:50px;text-align:right;">
<a href="javascript:;" onclick="popup('cache.php','600','500');"><button type="button" class="btn btn-info btn-lg" title="Cache TMDb data for videos." id="adminButtons"><span class="glyphicon glyphicon-download-alt"></span> Parse TMDb</button></a>
<br>
<a href="./?admin&vdata"><button type="button" class="btn btn-info btn-lg" title="Browse cached video data." id="adminButtons"><span class="glyphicon glyphicon-film"></span> Video Data</button></a>
<br>
<a href="http://newagesoldier.com" target="new"><button type="button" class="btn btn-info btn-lg" title="Visit Support Site" id="adminButtons"><span class="glyphicon glyphicon-info-sign"></span> Support</button></a>
<br>
<button type="submit" name="save_settings" class="btn btn-success btn-lg" title="Save Settings"><span class="glyphicon glyphicon-floppy-disk"></span> Save Settings</button>
</div>
</form>

</div>