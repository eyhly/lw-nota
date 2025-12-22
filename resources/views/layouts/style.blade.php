  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte3/dist/css/adminlte.min.css') }}">
<style>

table{
  text-transform: uppercase;
}

th{
  font-size: 0.88em;
}
td{
  font-size: 1em;
}

input{
  font-size: 1em;
  text-transform: uppercase;
}
.switch {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: space-between;
  width: 4em;      /* lebarnya dinamis */
  height: 1.5em;   /* tinggi toggle */
  font-size: 0.8em; /* teks mengikuti skala toggle */
  font-weight: bold;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: relative;
  flex-shrink: 0;
  width: 4.2em;     /* ukuran bulatan toggle */
  height: 1.5em;
  background-color: #ccc;
  border-radius: 1.5em;
  transition: background-color 0.3s;
  cursor: pointer;
}

.slider:before {
  content: "";
  position: absolute;
  height: 1.2em;
  width: 1.2em;
  left: 0.15em;
  top: 0.15em;
  background-color: white;
  border-radius: 50%;
  transition: transform 0.3s;
}

/* Geser bulatan saat checked */
input:checked + .slider:before {
  transform: translateX(2.7em);
}

/* Background saat ON */
input:checked + .slider {
  background-color: #28a745;
}

/* Tulisan “Sudah / Belum” */
.switch .switch-text {
  z-index: 1;
  white-space: nowrap;
}

input:checked ~ .switch-text {
  order: -1;       /* geser teks ke kiri */
  margin-left: 0.5em;
}

input:not(:checked) ~ .switch-text {
  order: 1;        /* geser teks ke kanan */
  margin-right: 0.5em;
}

</style>
