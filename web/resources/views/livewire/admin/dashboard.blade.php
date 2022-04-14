{{-- <div class="container">
    <h1 class="title">
        dashboard
    </h1>
    <p class="subtitle">
        <livewire:auth.logout/>
    </p>
    di
</div> --}}

<style>
    .accordion {
      background-color: #eee;
      color: #444;
      cursor: pointer;
      padding: 18px;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
    }
    
    .active, .accordion:hover {
      background-color: #ccc;
    }
    
    .accordion:after {
      content: '\002B';
      color: #777;
      font-weight: bold;
      float: right;
      margin-left: 5px;
    }
    
    .active:after {
      content: "\2212";
    }
    
    .panel {
      padding: 0 18px;
      background-color: white;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.2s ease-out;
    }
    </style>

<div class="section">
    <div class="columns">
        <div class="column is-mobile is-2" id="dashboard-menu">
            @include('layouts.menu', ['username' => $user->username])
        </div>
        <div class="column is-mobile" id="overview-dashboard">
           <h1 class="title">
               Overview
           </h1>
            <div class="content">
                <div class="rekapitulasi-fisik-dan-keuangan">
                    <h2 class="title has-text-centered">
                        Rekapitulasi Fisik dan Keuangan
                    </h2>
                    <div class="sekretariat box">
                        <h3 class="title">
                            Sekretariat
                        </h3>
                        <div style="overflow-y: scroll; height:400px;">

                            <div class="table-container">
                                <table class="table is-bordered is-striped is-narrow">
                                    <thead>
                                        <tr>
                                            <th colspan="1" rowspan="2">
                                                Sub Kegiatan
                                            </th>
                                            <th colspan="2" rowspan="1">
                                                Realisasi
                                            </th>
                                            
                                        </tr>
                                        <tr>
                                            <th>
                                                Pisikal
                                            </th>
                                            <th>
                                                Keuangan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($summary as $sum)
                                        <tr>
                                            <td>{{ $sum->activity }}</td>
                                            <td> 
                                                @isset($sum->physical)
                                                    {{ $sum->physical }}%
                                                @endempty

                                                @empty($sum->physical)
                                                    N/A
                                                @endempty
                                            </td>
                                            <td> 
                                                @isset($sum->finance)
                                                    {{ $sum->finance }}%
                                                @endempty

                                                @empty($sum->finance)
                                                    N/A
                                                @endempty
                                            </td>
                                            
                
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;
    
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        } 
      });
    }
    </script>