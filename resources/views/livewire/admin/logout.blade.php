<button class="btn btn-square btn-sm btn-error px-10">
    <a onclick="logout()" class="list-group-item active flex gap-2" style="cursor: pointer"><i class="bi bi-box-arrow-left"></i>Logout</a>
</button>

<script>
    function logout() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-error mx-4',
                cancelButton: 'btn btn-primary mx-4'
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: 'Keluar dari akun',
            text: "Apakah kamu yakin ingin logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Tidak, batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('logout');
                swalWithBootstrapButtons.fire({
                    title: 'Logout berhasil!',
                    text: 'Anda telah keluar dari akun.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    'Dibatalkan',
                    'Anda tetap berada di akun Anda :)',
                    'info'
                );
            }
        });
    }
</script>