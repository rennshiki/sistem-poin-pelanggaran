<dialog id="delete-confirmation-dialog" class="w-[calc(100%-2rem)] max-w-md rounded-2xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-950/50">
    <div class="p-6 sm:p-7">
        <div class="flex items-start gap-4">
            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600" aria-hidden="true">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008v.008H12v-.008ZM10.3 3.86 1.98 18.25A1.5 1.5 0 0 0 3.28 20.5h17.44a1.5 1.5 0 0 0 1.3-2.25L13.7 3.86a1.96 1.96 0 0 0-3.4 0Z" />
                </svg>
            </span>
            <div>
                <h2 id="delete-confirmation-title" class="text-lg font-semibold">Konfirmasi penghapusan</h2>
                <p id="delete-confirmation-message" class="mt-2 text-sm leading-6 text-slate-600"></p>
            </div>
        </div>
        <div class="mt-7 flex justify-end gap-3">
            <button type="button" id="cancel-delete" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" autofocus>
                Batal
            </button>
            <button type="button" id="confirm-delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2">
                Ya, hapus
            </button>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const dialog = document.getElementById('delete-confirmation-dialog');
        const message = document.getElementById('delete-confirmation-message');
        const cancelButton = document.getElementById('cancel-delete');
        const confirmButton = document.getElementById('confirm-delete');
        let pendingForm = null;

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('form[data-confirm]');
            if (!form) return;

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();
            pendingForm = form;
            message.textContent = form.dataset.confirm;
            dialog.showModal();
        }, true);

        cancelButton.addEventListener('click', function () {
            dialog.close();
        });

        confirmButton.addEventListener('click', function () {
            if (!pendingForm) return;

            const form = pendingForm;
            pendingForm = null;
            form.dataset.confirmed = 'true';
            dialog.close();
            form.requestSubmit();
        });

        dialog.addEventListener('close', function () {
            pendingForm = null;
        });
    })();
</script>
