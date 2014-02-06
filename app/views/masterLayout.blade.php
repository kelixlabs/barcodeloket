<html>
<head>
	<title></title>
	{{HTML::style('assets/js/uniform/themes/aristo/css/uniform.aristo.css')}}
	{{HTML::style('assets/js/jquery-ui/css/redmond/jquery-ui-1.10.4.custom.min.css')}}
</head>
<body>
	{{Form::open(array('url'=>'index','id'=>'formLayanan'))}}
	{{Form::hidden('reprint', '0', array('id'=>'reprint'))}}
	<p id="uniradio">
		{{Form::radio('layanan', 'Pemohon', TRUE, array('id'=>'ybs','class'=>'nouniform'))}}
		{{Form::label('ybs', 'Pemohon')}}
		{{Form::radio('layanan', 'BiroJasa', FALSE, array('id'=>'bu','class'=>'nouniform'))}}
		{{Form::label('bu', 'Biro Jasa')}}
	</p>
	<p>No. Permohonan : {{Form::input('text', 'noform','',array('id'=>'noform'))}}</p>
	<p>Nama : {{Form::input('text', 'nama','',array('id'=>'nama','disabled'=>'disabled'))}}</p>
	<p>Tgl. Lahir : {{Form::input('text', 'bod','',array('id'=>'bod','disabled'=>'disabled'))}}</p>
	<p>Jenis Permohonan : {{Form::input('text', 'formtype','',array('id'=>'formtype','disabled'=>'disabled'))}}</p>
	<p id="antrian">No. Antrian : {{Form::input('text', 'antrian','',array('id'=>'tantrian'))}}</p>
	<p id="kodebu">Kode Biro Jasa : {{Form::input('text', 'kodebu','',array('id'=>'tkodebu'))}}</p>
	<p>
		{{Form::reset('Clear', array('id'=>'clear','class'=>'nouniform'))}}
		{{Form::submit('Cetak', array('id'=>'cetak','class'=>'nouniform'))}}
	</p>
	{{Form::close()}}
	<div id="cetakUlang" title="Cetak Ulang?">
		<p>Data sudah tersimpan. Cetak Ulang?</p>
	</div>
{{HTML::script('assets/js/jquery-1.10.2.min.js')}}
{{HTML::script('assets/js/jquery-ui/js/jquery-ui-1.10.4.custom.min.js')}}
{{HTML::script('assets/js/uniform/jquery.uniform.min.js')}}
<script type="text/javascript">
$(document).ready(function() {
	$("input").not(".nouniform").uniform();
	$("#uniradio").buttonset();
	$("input[type=submit],input[type=reset]").button();
	$("#kodebu").hide('slow');
	$("#cetak").hide('slow');
	$("#noform").focus();
	$("#reprint").val('0');

	$( "#cetakUlang" ).dialog({
		autoOpen: false,
		resizable: false,
		width: 500,
		modal: true,
		buttons: {
			"Cetak": function() {
				$( this ).dialog( "close" );
				$("#reprint").val('1');
				$("#tantrian").focus();
			},
			Cancel: function() {
				$( this ).dialog( "close" );
				$("#reprint").val('0');
				$("#noform").val("");
				$("#nama").val("");
				$("#bod").val("");
				$("#formtype").val("");
				$("#tantrian").val("");
				$("#tkodebu").val("");
				$("#cetak").hide('slow');
				$("#noform").focus();
			}
		}
	});

	$('input:radio').change(function(event) {
		if($(this).val() == "Pemohon"){
			$("#antrian").show('slow');
			$("#kodebu").hide('slow');
		}
		else{
			$("#antrian").hide('slow');
			$("#kodebu").show('slow');
		}
	});

	$("#noform").keypress(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if(code == 13 || code == 9) { //Enter keycode
			event.preventDefault();
			$.ajax({
				url: "{{URL::to('/pemohon')}}",
				type: 'POST',
				data: {noform: $(this).val()},
			})
			.done(function(retdata) {
				var datanya = $.parseJSON(retdata);
				console.log(retdata);
				if (datanya[0].pesan == "sukses") {
					$("#nama").val(datanya[0].data[0].Name);
					var bod = datanya[0].data[0].BirthDate;
					$("#bod").val(bod.substring(6,8) + "-" + bod.substring(4,6) + "-" + bod.substring(0,4));
					var formtype = parseInt(datanya[0].data[0].FormTypeXID);
					switch (formtype){
						case 11:
							$("#formtype").val("Baru - Paspor Biasa");
							break;
						case 12:
							$("#formtype").val("Baru - Paspor TKI");
							break;
						case 21:
							$("#formtype").val("Penggantian - Habis Berlaku");
							break;
						case 22:
							$("#formtype").val("Penggantian - Halaman Penuh");
							break;
						case 23:
							$("#formtype").val("Penggantian - Hilang");
							break;
						case 24:
							$("#formtype").val("Penggantian - Rusak Masih Berlaku krn Kelalaian")
							break;
						case 26:
							$("#formtype").val("Penggantian - Hilang Bencana Alam")
							break;
						case 27:
							$("#formtype").val("Penggantian - Hilang Kapal Tenggelam");
							break;
						case 28:
							$("#formtype").val("Penggantian 24H/48H eks Pemegang 24H/48H/SPLP");
							break;
						case 29:
							$("#formtype").val("Penggantian - Rusak Tenggelam/Bencana Alam");
							break;
						case 31:
							$("#formtype").val("Perubahan - Nama");
							break;
						case 32:
							$("#formtype").val("Perubahan - Alamat Tempat Tinggal");
							break;
						case 33:
							$("#formtype").val("Perubahan - Lain-lain");
							break;
						case 201:
							$("#formtype").val("Penggantian Hilang Habis Berlaku");
							break;
						case 202:
							$("#formtype").val("Penggantian Rusak Habis Berlaku");
							break;
					}
					if ("loket" in datanya[0]) {
						$("#tantrian").val(datanya[0].loket[0].antrian);
						$("#antrian").show('slow');
						$("#tkodebu").val('');
						$("#kodebu").hide('slow')
						$( "#cetakUlang" ).dialog("open");
						//alert(datanya[0].loket[0].antrian);
					} else if ("bu" in datanya[0]) {
						$("#tantrian").val('');
						$("#antrian").hide('slow');
						$("#tkodebu").val(datanya[0].bu[0].antrian);
						$("#kodebu").show('slow');
						$( "#cetakUlang" ).dialog("open");
					} else {};
					$("#tantrian").focus();
					$("#cetak").show('slow');
				} else{
					alert(datanya[0].data);
					$("#noform").val("");
					$("#nama").val("");
					$("#bod").val("");
					$("#formtype").val("");
					$("#tantrian").val("");
				};
			})
			.fail(function() {
				alert("error");
			});
		}
	});
	
	$("#tantrian").focusin(function(event) {
		$("#cetak").show('slow');
	});

	$("#tkodebu").focusin(function(event) {
		$("#cetak").show('slow');
	});

	$("#tantrian").keypress(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if(code == 13) { 
			event.preventDefault();
		}
	});

	$("#tkodebu").keypress(function(event) {
		var code = (event.keyCode ? event.keyCode : event.which);
		if(code == 13) { 
			event.preventDefault();
		}
	});

	$("#noform").focusin(function(event) {
		$("#cetak").hide('slow');
	});
});
</script>
</body>
</html>