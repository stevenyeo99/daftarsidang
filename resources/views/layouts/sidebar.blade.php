<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">NAVIGASI UTAMA</li>
      {{-- @if (Gate::allows('is-admin-group') --}}
        <li class="treeview {{ $request_css or '' }}">
          @if (!Gate::allows('is-meteor') && !Gate::allows('is-prodi-dosen') && !Gate::allows('is-library-admin') && !Gate::allows('is-library-user'))
            <a href="#">
              <i class="fa fa-dashboard"></i> <span>Pendaftaran</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
          @endif
          @if (Gate::allows('is-admin-group'))
            <ul class="treeview-menu">
              <li class="{{ $kp_request_css or '' }}">
                <a href="{{ route('request.kp') }}">
                  <i class="fa fa-circle-o"></i>
                   KP
                </a>
             </li>
              <li class="{{ $skripsi_request_css or '' }}">
                <a href="{{ route('request.skripsi') }}">
                  <i class="fa fa-circle-o"></i>
                   Skripsi / Tesis 
                </a>
             </li>
            </ul>
          @endif
          @if (Gate::allows('is-student'))
            <ul class="treeview-menu">
              <li class="{{ $kp_request_css or '' }}">
                <a href="{{ route('student.request.kp') }}">
                  <i class="fa fa-circle-o"></i>
                   KP
                </a>
             </li>
              <li class="{{ $skripsi_request_css or '' }}">
                <a href="{{ route('student.request.skripsi') }}">
                  <i class="fa fa-circle-o"></i>
                   Skripsi / Tesis 
                </a>
             </li>
            </ul>
          @endif
          @if(Gate::allows('is-prodi-admin'))
            <ul class="treeview-menu">
              <li class="{{ $kp_request_css or '' }}">
                <a href="{{ route('prodi.request.kp') }}">
                  <i class="fa fa-circle-o"></i>
                  KP
                </a>
              </li>
              <li class="{{ $skripsi_request_css or '' }}">
                <a href="{{ route('prodi.request.skripsi') }}">
                  <i class="fa fa-circle-o"></i>
                    Skripsi / Tesis
                </a>
              </li>
            </ul>
          @endif
          @if(Gate::allows('is-finance-group'))
            <ul class="treeview-menu">
              <li class="{{ $skripsi_request_css or '' }}">
                <a href="{{ route('finance.request.skripsi') }}">
                  <i class="fa fa-circle-o"></i>
                    Skripsi / Tesis
                </a>
              </li>
            </ul>
          @endif
        </li>
        @if((!Gate::allows('is-library-admin') && !Gate::allows('is-library-user')) && (!Gate::allows('is-prodi-admin') && !Gate::allows('is-prodi-dosen')) && !Gate::allows('is-finance-group'))
        <li class="treeview {{ $master_css or '' }}">
          <a href="#">
            @if (Gate::allows('is-admin-group') || Gate::allows('is-meteor') && (!Gate::allows('is-prodi-admin') && !Gate::allows('is-prodi-dosen')))
              <i class="fa fa-gears"></i> <span>Master</span>
            @endif
            @if (Gate::allows('is-student'))
              <i class="fa fa-file-text"></i> <span>Koleksi</span>
            @endif
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          @if (Gate::allows('is-admin-group'))
            <ul class="treeview-menu">
              <li class="{{ $semester_css or '' }}">
                <a href="{{ route('semesters') }}">
                  <i class="fa fa-circle-o"></i>
                   Semester
                </a>
             </li>
              <li class="{{ $faculty_css or '' }}">
                <a href="{{ route('faculties') }}">
                  <i class="fa fa-circle-o"></i>
                   Fakultas 
                </a>
             </li>
              <li class="{{ $program_study_css or '' }}">
                <a href="{{ route('prodis') }}">
                  <i class="fa fa-circle-o"></i>
                  Program Studi
                </a>
             </li>
              <li class="{{ $student_css or '' }}">
                <a href="{{ route('students') }}">
                  <i class="fa fa-circle-o"></i>
                  Mahasiswa
                </a>
             </li>
             <li class="{{ $ruangan_sidang_css or '' }}">
                <a href="{{ route('ruangan') }}">
                  <i class="fa fa-circle-o"></i>
                  Ruangan Sidang
                </a>
             </li>
            </ul>
          @endif
          @if (Gate::allows('is-meteor'))
            <ul class="treeview-menu">
              <li class="{{ $skripsi_student_css or '' }}">
                <a href="{{ route('meteor.skripsi.students') }}">
                  <i class="fa fa-circle-o"></i>
                  Mahasiswa Skripsi
                </a>
             </li>
              <li class="{{ $tesis_student_css or '' }}">
                <a href="{{ route('meteor.tesis.students') }}">
                  <i class="fa fa-circle-o"></i>
                  Mahasiswa Tesis
                </a>
             </li>
            </ul>
          @endif
          @if (Gate::allows('is-student'))
            <ul class="treeview-menu">
              <li class="{{ $attachment_css or '' }}">
                <a href="{{ route('student.attachment') }}">
                  <i class="fa fa-circle-o"></i>
                  Lampiran Wajib
                </a>
              </li>
              <li class="{{ $certificate_css or '' }}">
                <a href="{{ route('student.certificate') }}">
                  <i class="fa fa-circle-o"></i>
                   Sertifikasi
                </a>
              </li>
              <li class="{{ $achievement_css or '' }}">
                <a href="{{ route('student.achievement') }}">
                  <i class="fa fa-circle-o"></i>
                   Prestasi
                </a>
              </li>
            </ul>
          @endif
        </li>
        @endif
        @if (Gate::allows('is-admin'))
          <li class="treeview {{ $admin_css or '' }}">
            <a href="#">
              <i class="fa fa-gg"></i> <span>Admin</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $user_css or '' }}">
                <a href="{{ route('users') }}">
                  <i class="fa fa-circle-o"></i>
                   User
                </a>
              </li>
            </ul>
          </li>
        @endif
        @if (Gate::allows('is-student'))
          <li class="{{ $student_profile_css or '' }}">
            <a href="{{ route('student.profile') }}">
              <i class="fa fa-gg"></i> <span>Profil</span>
            </a>
          </li>
        @endif
        <!-- do hardcover section -->
        @if (Gate::allows('is-admin-group'))
          <li class="treeview {{ $hardcover_css or '' }}">
              @if (!Gate::allows('is-meteor'))
                <a href="#">
                  <i class="fa fa-book"></i> <span>Hardcover</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
              @endif
              @if(Gate::allows('is-admin-group'))
                <ul class="treeview-menu">
                  <li class="{{ $hardcover_kp_css or '' }}">
                    <a href="{{ route('admin.hardcover_kp') }}">
                      <i class="fa fa-circle-o"></i>
                      KP
                    </a>
                  </li>
                  <li class="{{ $hardcover_skripsi_css or '' }}">
                    <a href="{{ route('admin.hardcover_skripsi') }}">
                      <i class="fa fa-circle-o"></i>
                      Skripsi
                    </a>
                  </li>
                  <li class="{{ $hardcover_tesis_css or '' }}">
                    <a href="{{ route('admin.hardcover_tesis') }}">
                      <i class="fa fa-circle-o"></i>
                      Tesis
                    </a>
                  </li>
                </ul>
              @endif
          </li>
        @endif
        <!-- end hardcover section -->
        <!-- start turnitin section -->
        @if (Gate::allows('is-library-admin') || Gate::allows('is-library-user'))
            <li class="treeview {{ $turnitin_css or '' }}">
                <a href="#">
                  <i class="fa fa-file"></i> <span>Turnitin</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
              <ul class="treeview-menu">
                  <li class="{{ $turnitin_kp_css or '' }}">
                      <a href="{{ route('turnitin_kp') }}">
                          <i class="fa fa-circle-o"></i>
                          KP
                      </a>
                  </li>

                  <li class="{{ $turnitin_skripsi_css or '' }}">
                    <a href="">
                        <i class="fa fa-circle-o"></i>
                        Skripsi
                    </a>
                  </li>
              </ul>
            </li>
        @endif
        {{-- @endif --}}
        <!--- end turnitin section -->
        <!-- library crud user -->
        @if (Gate::allows('is-library-admin'))
            <li class="treeview {{ $admin_css or '' }}">
              <a href="#">
                <i class="fa fa-gg"></i> <span>Perpus Staff</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ $user_css or '' }}">
                  <a href="{{ route('library_staff') }}">
                    <i class="fa fa-circle-o"></i>
                    User
                  </a>
                </li>
              </ul>
            </li>
        @endif
        <!-- end library crud user section -->
        @if (Gate::allows('is-finance-admin'))
          <li class="treeview {{ $admin_css or '' }}">
            <a href="#">
                <i class="fa fa-gg"></i> <span>Users</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ $user_css or '' }}">
                  <a href="{{ route('finance_user') }}">
                    <i class="fa fa-circle-o"></i>
                    User
                  </a>
                </li>
              </ul>
          </li>
        @endif
        <!-- finance crud user -->

        <!-- end finance crud user section -->


        <!-- prodi section for super admin only -->
        <!-- @if(Gate::allows('is-superadmin'))
          <li class="treeview {{ $admin_dosen_css or '' }}">
            <a href="#">
              <i class="fa fa-users"></i> <span>Prodi User</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $user_dosen_css or '' }}">
                <a href="{{ route('baak.prodi_users') }}">
                  <i class="fa fa-circle-o"></i>
                  User
                </a>
              </li>
            </ul>
          </li>
        @endif -->

        @if(Gate::allows('is-prodi-admin'))
          <li class="treeview {{ $admin_dosen_css or '' }}">
            <a href="#">
              <i class="fa fa-users"></i> <span>Prodi User</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $user_dosen_css or '' }}">
                <a href="{{ route('dosen') }}">
                  <i class="fa fa-circle-o"></i>
                  User
                </a>
              </li>
            </ul>
          </li>
        @endif

        <!-- penjadwalan section -->
        @if(Gate::allows('is-prodi-admin') && ! Gate::allows('is-meteor'))
          <li class="treeview {{ $penjadwalan_css or '' }}">
            <a href="#">
              <i class="fa fa-calendar"></i> <span>Penjadwalan Sidang</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $jadwalKP_css or '' }}">
                  <a href="{{ route('prodi.penjadwalan.kp') }}">
                    <i class='fa fa-circle-o'></i>
                    KP
                  </a>
              </li>

              <li class="{{ $jadwalSkripsi_css or '' }}">
                  <a href="{{ route('prodi.penjadwalan.skripsi') }}">
                    <i class="fa fa-circle-o"></i>
                    Skripsi
                  </a>
              </li>

              <li class="{{ $jadwalTesis_css or '' }}">
                  <a href="{{ route('prodi.penjadwalan.tesis') }}">
                    <i class="fa fa-circle-o"></i>
                    Tesis
                  </a>
              </li>
            </ul>
          </li>
        @endif

        @if(Gate::allows('is-admin-group') && !Gate::allows('is-meteor'))
          <li class="treeview {{ $penjadwalan_css or '' }}">
            <a href="#">
              <i class="fa fa-calendar"></i> <span>Penjadwalan Sidang</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $jadwalKP_css or '' }}">
                <a href="{{ route('baak.penjadwalan.kp') }}">
                  <i class="fa fa-circle-o"></i>
                  KP
                </a>
              </li>

              <li class="{{ $jadwalSkripsi_css or '' }}">
                <a href="{{ route('baak.penjadwalan.skripsi') }}">
                  <i class="fa fa-circle-o"></i>
                  Skripsi
                </a>
              </li>

              <li class="{{ $jadwalTesis_css or '' }}">
                <a href="{{ route('baak.penjadwalan.tesis') }}">
                  <i class="fa fa-circle-o"></i>
                  Tesis
                </a>
              </li>
            </ul>
          </li>
        @endif
        <!-- penjadwalan end section -->

        <!-- berita acara section -->
        @if(Gate::allows('is-prodi-dosen'))
          <li class="treeview {{$beritaAcaraMaster_css or '' }}">
              <a href="#">
                  <i class="fa fa-tasks"></i> <span>Berita Acara</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ $beritaAcaraKP_css or '' }}">
                  <a href="{{ route('prodi.berita.acara.kp') }}">
                    <i class="fa fa-circle-o"></i>
                    KP
                  </a>
                </li>

                <li class="{{ $beritaAcaraSkripsi_css or '' }}">
                  <a href="{{ route('prodi.berita.acara.skripsi') }}">
                    <i class="fa fa-circle-o"></i>
                    Skripsi
                  </a>
                </li>

                <li class="{{ $beritaAcaraTesis_css or '' }}">
                  <a href="{{ route('prodi.berita.acara.tesis') }}">
                    <i class="fa fa-circle-o"></i>
                    Tesis
                  </a>
                </li>
              </ul>
          </li>
        @elseif(Gate::allows('is-prodi-admin'))
        <li class="treeview {{$beritaAcaraMaster_css or '' }}">
            <a href="#">
                <i class="fa fa-tasks"></i> <span>Berita Acara</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ $beritaAcaraKP_css or '' }}">
                  <a href="{{ route('prodi_admin.berita.acara.kp') }}">
                    <i class="fa fa-circle-o"></i>
                    KP
                  </a>
              </li>

              <li class="{{ $beritaAcaraSkripsi_css or '' }}">
                  <a href="{{ route('prodi_admin.berita.acara.skripsi') }}">
                    <i class="fa fa-circle-o"></i>
                    Skripsi
                  </a>
              </li>

              <li class="{{ $beritaAcaraTesis_css or '' }}">
                  <a href="{{ route('prodi_admin.berita.acara.tesis') }}">
                    <i class="fa fa-circle-o"></i>
                    Tesis
                  </a>
              </li>
            </ul>
        </li>
        @elseif(Gate::allows('is-admin-group') && !Gate::allows('is-meteor'))
        <li class="treeview {{$beritaAcaraMaster_css or '' }}">
              <a href="#">
                  <i class="fa fa-tasks"></i> <span>Berita Acara</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
              </a>
              <ul class="treeview-menu">
                <li class="{{ $beritaAcaraKP_css or '' }}">
                  <a href="{{ route('baak.berita_acara.kp') }}">
                    <i class="fa fa-circle-o"></i>
                    KP
                  </a>
                </li>

                <li class="{{ $beritaAcaraSkripsi_css or '' }}">
                  <a href="{{ route('baak.berita_acara.skripsi') }}">
                    <i class="fa fa-circle-o"></i>
                    Skripsi
                  </a>
                </li>

                <li class="{{ $beritaAcaraTesis_css or '' }}">
                  <a href="{{ route('baak.berita_acara.tesis') }}">
                    <i class="fa fa-circle-o"></i>
                    Tesis
                  </a>
                </li>
              </ul>
          </li>
        @endif
        <!-- berita acara end section -->
      </ul>
  </section>
  <!-- /.sidebar -->
</aside>