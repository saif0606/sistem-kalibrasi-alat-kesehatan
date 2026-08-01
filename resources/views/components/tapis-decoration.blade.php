{{-- ==========================================================
     REUSABLE COMPONENT — Tapis Decoration (satu-satunya sistem
     dekorasi identitas visual di seluruh website)

     Menggantikan <x-tapis-motif> + <x-decorative-bg> yang lama.
     Visualnya: pita diagonal gradasi biru→hijau, dipadukan di satu
     sudut dengan ikon crosshair (bidik presisi) dan ruas skala
     penggaris — mengambil bahasa visual dari logo UPTD sendiri,
     bukan motif generik (lingkaran/dot-grid/garis acak).

     Ditempatkan di sudut saja, opacity rendah (lihat CSS), tidak
     pernah menimpa teks (pointer-events: none, z-index rendah).

     Props:
       corners (string) — 'tl-br' (default) atau 'tr-bl'
         Sudut PERTAMA -> pita polos (tapis-ribbon.svg)
         Sudut KEDUA   -> pita + crosshair + skala (tapis-mark.svg)
========================================================== --}}
@props([
    'corners' => 'tl-br',
])

@php
    [$first, $second] = explode('-', $corners);
    $posClass = [
        'tl' => 'tapis-corner-tl',
        'tr' => 'tapis-corner-tr',
        'bl' => 'tapis-corner-bl',
        'br' => 'tapis-corner-br',
    ];
@endphp

<div class="tapis-decoration-wrap" aria-hidden="true">
    <img src="{{ asset('images/tapis-ribbon.svg') }}"
         class="tapis-decoration tapis-decoration-ribbon {{ $posClass[$first] }}" alt="">
    <img src="{{ asset('images/tapis-mark.svg') }}"
         class="tapis-decoration tapis-decoration-mark {{ $posClass[$second] }}" alt="">
</div>
