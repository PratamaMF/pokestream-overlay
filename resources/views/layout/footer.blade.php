

        <footer class="py-4 bg-white border-top mt-auto">
          <div class="container-fluid px-4">
            <div
              class="d-flex align-items-center justify-content-between small"
            >
              <div class="text-muted">
                <span>Copyright &copy; 2026</span>
                <a
                  href="#"
                  class="text-decoration-none fw-bold ms-1"
                  style="color: #001f3f"
                  >Poke Stream Overlay</a
                >
                <span class="mx-2">&middot;</span>
                <span
                  >Created by
                  <span class="text-dark fw-medium"
                    >M. Fajar Pratama</span
                  ></span
                >
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="{{asset('pradash')}}/js/scripts.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="{{asset('pradash')}}/js/charts.js"></script>
    <script src="{{asset('pradash')}}/js/pradash.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="{{asset('pradash')}}/js/datatables-simple-demo.js"></script>
    @if(session('SA-success'))
    <script>
        Swal.fire({
            title: "Good job!",
            text: "{{ session('SA-success') }}",
            icon: "success",
            confirmButtonColor: "#162d4d",
        });
    </script>
    @endif

    @if(session('SA-error'))
    <script>
        Swal.fire({
            title: "Oops...",
            text: "{{ session('SA-error') }}",
            icon: "error",
            confirmButtonColor: "#ef4444",
        });
    </script>
    @endif
    <script type="text/javascript">
      $(function(){
          $(document).on('click', '#delete', function(e){
              e.preventDefault();
              
              var form = $(this).closest("form");

              Swal.fire({
                  title: "Are you sure?",
                  text: "You won't be able to revert this!",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#162d4d",
                  cancelButtonColor: "#8898aa",
                  confirmButtonText: "Yes, delete it!",
                  cancelButtonText: "Cancel"
              }).then((result) => {
                  if (result.isConfirmed) {
                      form.submit();
                      
                      Swal.fire({
                          title: "Deleted!",
                          text: "Your data has been deleted.",
                          icon: "success"
                      });
                  }
              });
          });
      });
  </script>

  @stack('scripts')
  </body>
</html>
