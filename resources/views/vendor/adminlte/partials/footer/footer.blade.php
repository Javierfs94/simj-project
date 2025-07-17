<footer class="main-footer py-2">
    <div class="container-fluid">
        <div class="row">
            {{-- Columna 1: Fecha de cierre --}}
            <div class="col-md-4 mb-2 mb-md-0 text-left">
                <small>
                    <i class="fas fa-clock"></i>
                    Cierre de sesión: {{ now()->addMinutes((int) config('session.lifetime'))->format('H:i') }}
                </small>
            </div>

            {{-- Columna 2: Enlace --}}
            <div class="col-md-4 mb-2 mb-md-0 text-center">
                ©{{ now()->year }}
                <a href="https://solucionesinformaticasmj.com/" target="_blank">
                    Soluciones Informáticas M.J, S.C.A.
                </a>
            </div>

            {{-- Columna 3: Versión --}}
            <div class="col-md-4 text-right">
                <div class="bg-light p-2">
                    <p>
                        <span class="footer-version">Versión</span>
                        <span class="footer-version-value">{{ config('app.version') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
