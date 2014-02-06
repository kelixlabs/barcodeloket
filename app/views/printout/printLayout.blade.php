<html>
<head>
	<title></title>
	<!--link href="print.css" rel="stylesheet" media="print"/-->
	{{HTML::style('assets/css/print.css', array('media'=>'all'))}}
	<link href="print.css" rel="stylesheet"/>
</head>
<body>
<div class="logo">
	{{HTML::image('assets/css/logo.png','',array('width'=>'120px'))}}
</div>

<div class="header">
	<h4>
		KEMENTERIAN HUKUM DAN HAM RI<br/>
		KANTOR WILAYAH JAWA TIMUR<br/>
		KANTOR IMIGRASI KELAS I KHUSUS SURABAYA
	</h4>
	<span>
		Jl. Jend. S. Parman no. 58-A, Waru, Sidoarjo<br/>
		Email : kanim_surabaya@imigrasi.go.id
	</span>
	<h5>TANDA TERIMA LOKET PENGAJUAN PASPOR</h5>
</div>
<div class='nomor'>
	<h1>{{$kode}}</h1>
</div>
<div class="clear"></div><hr/>
<div class="content">
	<p><span class="label">NAMA </span> : {{$nama}}</p>
	<p><span class="label">Tanggal Lahir </span> : {{$dob}}</p>
	<p><span class="label">Jenis Permohonan </span> : {{$formtype}}</p>
	<p><span class="label">No. Permohonan </span> : {{$formno}}</p>
	<div align="center">{{DNS1D::getBarcodeSVG($formno, 'C128',3,100)}}</div>
</div><hr/>
<div class="footer">
	<h4>INFO</h4>
	<ul>
		<li>PASPOR selesai 3 hari setelah PEMBAYARAN</li>
		<li>
			Cek via SMS sebelum melakukan pengambilan paspor dengan cara : <br/>
			ketik <span class="paspor">PASPOR $noform</span> kirim ke 081230056677
		</li>
	</ul>
</div>
<script type="text/javascript">
window.print();
document.location.href = "{{URL::to('/')}}";
</script>

</body>
</html>