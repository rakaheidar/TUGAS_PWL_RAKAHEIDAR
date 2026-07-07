<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">

    <!-- FORM CHECKOUT -->
    <div class="col-md-6">

        <?= form_open('buy', 'class="row g-3"') ?>

        <?= form_hidden('username', session()->get('username')) ?>

        <?= form_input([
            'type' => 'hidden',
            'name' => 'total_harga',
            'id' => 'total_harga'
        ]) ?>

        <div class="col-12">
            <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'nama',
                'id'       => 'nama',
                'class'    => 'form-control',
                'value'    => session()->get('username'),
                'readonly' => true
            ]) ?>
        </div>

        <div class="col-12">
            <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'  => 'alamat',
                'id'    => 'alamat',
                'class' => 'form-control'
            ]) ?>
        </div>

        <div class="col-12">
            <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
            <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
        </div>

        <div class="col-12">
            <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?>
            <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
        </div>

        <div class="col-12">
            <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'     => 'ongkir',
                'id'       => 'ongkir',
                'class'    => 'form-control',
                'readonly' => true
            ]) ?>
        </div>

        <div class="col-12">
            <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
            <?= form_input([
                'name'        => 'voucher_code',
                'id'          => 'voucher_code',
                'class'       => 'form-control',
                'placeholder' => 'FLASH10 / FLASH15 / MEMBER20'
            ]) ?>
            <small class="text-muted">Tersedia: FLASH10, FLASH15, MEMBER20</small>
            <div id="voucher_status" class="form-text"></div>
        </div>

        <div class="col-12">
            <?= form_submit(
                'submit',
                'Buat Pesanan',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?= form_close() ?>

    </div>

    <!-- TABEL PESANAN -->
    <div class="col-md-6">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($items)) :
                    foreach ($items as $index => $item) :
                ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>

                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td class="text-danger">Diskon Voucher</td>
                    <td class="text-danger">
                        <span id="diskon_voucher">- IDR 0</span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>PPN (11%)</td>
                    <td><span id="ppn">IDR 0</span></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Biaya Admin</td>
                    <td><span id="biaya_admin">IDR 0</span></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td class="text-success"><strong>Subtotal (+PPN+Admin-Voucher)</strong></td>
                    <td class="text-success"><strong><span id="subtotal_after">IDR 0</span></strong></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td>Ongkir</td>
                    <td><span id="ongkir_display">IDR 0</span></td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                    <td><strong>Grand Total (incl. Ongkir)</strong></td>
                    <td>
                        <strong>
                            <span id="total">
                                <?= number_to_currency($total, 'IDR') ?>
                            </span>
                        </strong>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>

<?= $this->endSection() ?>
    <?= $this->section('script') ?>
    <script>
    $(document).ready(function() {

        // harus sama persis dengan app/Helpers/transaksi_helper.php
        const VOUCHERS = {
            'FLASH10': 0.10,
            'FLASH15': 0.15,
            'MEMBER20': 0.20
        };
        const PPN_RATE = 0.11;

        let ongkir = 0;
        let subtotal = <?= $total ?>;

        hitungTotal();

        function hitungBiayaAdmin(nilai) {
            if (nilai <= 20000000) return nilai * 0.006;
            if (nilai <= 40000000) return nilai * 0.008;
            return nilai * 0.01;
        }

        function formatIDR(nilai) {
            return `IDR ${Math.round(nilai).toLocaleString('id-ID')}`;
        }

        function hitungTotal() {
            const kode = $('#voucher_code').val().trim().toUpperCase();
            let diskonRate = 0;

            if (kode.length > 0) {
                if (VOUCHERS.hasOwnProperty(kode)) {
                    diskonRate = VOUCHERS[kode];
                    $('#voucher_status').removeClass('text-danger').addClass('text-success')
                        .text(`Voucher valid: diskon ${diskonRate * 100}%`);
                } else {
                    $('#voucher_status').removeClass('text-success').addClass('text-danger')
                        .text('Kode voucher tidak valid');
                }
            } else {
                $('#voucher_status').removeClass('text-success text-danger').text('');
            }

            const diskonVoucher = subtotal * diskonRate;
            const ppn = subtotal * PPN_RATE;
            const biayaAdmin = hitungBiayaAdmin(subtotal);

            const subtotalAfter = subtotal - diskonVoucher + ppn + biayaAdmin;
            const grandTotal = subtotalAfter + ongkir;

            $('#diskon_voucher').text(`- ${formatIDR(diskonVoucher)}`);
            $('#ppn').text(formatIDR(ppn));
            $('#biaya_admin').text(formatIDR(biayaAdmin));
            $('#subtotal_after').text(formatIDR(subtotalAfter));
            $('#ongkir_display').text(formatIDR(ongkir));
            $('#total').text(formatIDR(grandTotal));

            $('#ongkir').val(ongkir);
            $('#total_harga').val(grandTotal);
        }

        $('#voucher_code').on('input', function() {
            hitungTotal();
        });

        $('#kelurahan').select2({
            placeholder: 'Cari daerah tujuan',
            minimumInputLength: 3, 
            ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
        });
        $("#kelurahan").on('change', function () {
        let id_kelurahan = $(this).val();

        $("#layanan").empty();
        ongkir = 0;
        hitungTotal(); 

        $.ajax({
                url: "<?= site_url('ajax/costs') ?>", 
                dataType: "json",
                data: {
                    destination: id_kelurahan
                },
                success: function (data) { 
                    data.forEach(function (item) {
                        $("#layanan").append(
                            $('<option>', {
                                value: item.cost,
                                text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                            })
                        );
                    });
                }
            });
        });
        $("#layanan").on('change', function() {
            ongkir = parseInt($(this).val());
            hitungTotal();
        }); 
    });
    </script>
    <?= $this->endSection() ?>