<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

	<script type="text/javascript" src="src/js/form-builder.js"></script>



</head>

<body bgcolor="#FFFFFF">
	<div class="build-wrap"></div>
<script>
jQuery($ => {
  $('.build-wrap').formBuilder()
})
</script>
<form id="rendered-form">
	<div class="rendered-form">
		<div class="">
			<h1 id="control-4405901">Estudios</h1>
		</div>
		<div class="">
			<p id="control-8527625">Ejemplo de pantalla de Drag and Drop de FromBuilder<br></p>
		</div>
		<div class="fb-radio-group form-group field-radio-group-1543074675624">
			<label for="radio-group-1543074675624" class="fb-radio-group-label">Sucursal</label>
			<div class="radio-group">
				<div class="fb-radio"><input name="radio-group-1543074675624" id="radio-group-1543074675624-0" value="1" type="radio"><label for="radio-group-1543074675624-0">Matriz</label></div>
				<div class="fb-radio"><input name="radio-group-1543074675624" id="radio-group-1543074675624-1" value="2" type="radio"><label for="radio-group-1543074675624-1">OHF</label></div>
				<div class="fb-radio"><input name="radio-group-1543074675624" id="radio-group-1543074675624-2" value="3" type="radio"><label for="radio-group-1543074675624-2">Tepexpan</label></div>
			</div>
		</div>
		<div class="fb-select form-group field-select-1543074680539">
			<label for="select-1543074680539" class="fb-select-label">Departamento</label>
			<select class="form-control" name="select-1543074680539" id="select-1543074680539">
				<option value="1" selected="true" id="select-1543074680539-0">Laboratorio</option>
				<option value="2" id="select-1543074680539-1">Rayos RX y USG</option>
				<option value="3" id="select-1543074680539-2">Especiales</option>
			</select>
		</div>
		<div class="fb-textarea form-group field-textarea-1543074685039">
			<label for="textarea-1543074685039" class="fb-textarea-label">Comentarios</label>
			<textarea type="textarea" class="form-control" name="textarea-1543074685039" id="textarea-1543074685039"></textarea>
		</div>
		<div class="fb-button form-group field-button-1543074689540">
			<button type="button" name="button-1543074689540" id="button-1543074689540">Aceptar</button>
		</div>
	</div>
</form>

</body>

</html>


