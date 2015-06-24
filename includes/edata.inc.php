<div style="margin-left:auto;margin-right:auto;width:850px;position:relative;">

<div><a href="Javascript:;" onclick="history.back(-1)">< BACK</a></div>

<div class="adminPanels" id="admin" style="width:600px;">
<div class="panel panel-primary" style="min-width:600px;">
  <div class="panel-heading">
    <h3 class="panel-title">DATA_VIDEOPATH Video Data</h3>
  </div>

<form method="post">
<div class="panel-body">

<input name="id" type="hidden" class="form-control" value="DATA_ID">

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">TITLE</span><input name="title" type="text" class="form-control" placeholder="Video Title" value="DATA_TITLE"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">TMDB ID</span><input name="tmdb_id" type="text" class="form-control" value="DATA_TMDBID"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">IMDB ID</span><input name="imdb_id" type="text" class="form-control" value="DATA_IMDBID"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">POSTER</span><input name="poster_path" type="text" class="form-control" value="DATA_OPOSTER"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">GENRES</span><input name="genres" type="text" class="form-control" value="DATA_GENRES"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">RUNTIME</span><input name="runtime" type="text" class="form-control" value="DATA_RUNTIME"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">LANGUAGES</span><input name="spoken_languages" type="text" class="form-control" value="DATA_SPOKENLANG"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">TAGLINE</span><input name="tagline" type="text" class="form-control" value="DATA_TAGLINE"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">TRAILER</span><input name="trailers" type="text" class="form-control" value="DATA_TRAILER" placeholder="YouTube Video ID"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">COMPANIES</span><input name="production_companies" type="text" class="form-control" value="DATA_COMPANY" placeholder="Production Companies"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">RELEASED</span><input name="release_date" type="text" class="form-control" value="DATA_RELEASED" id="two"></div>

<div class="input-group" style="margin-top:10px;width:100%;"><span class="input-group-addon" style="width:120px;">OVERVIEW</span><textarea name="overview" class="form-control" style="height:150px;">DATA_OVERVIEW</textarea></div>

<button type="submit" name="save_vdata" class="btn btn-success btn-lg" title="Save Data" style="margin-top:10px;"><span class="glyphicon glyphicon-floppy-disk"></span> Save Data</button> <button type="submit" name="delete_vdata" class="btn btn-danger btn-lg" title="Delete Data" style="margin-top:10px;"><span class="glyphicon glyphicon-fire"></span> Delete Data</button>

</div>
</form>

</div>
</div>

</div>