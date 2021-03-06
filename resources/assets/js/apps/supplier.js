var supplierVue = new Vue ({
    el: '#supplierVue',
    data: {
        supplier: {},
        supplierList: [],
        statusDDL: [],
        bankDDL: [],
        providerDDL: [],
        productList: [],
        mode: '',
        search_supplier_query: '',
        active_page: 0,
        tableLoaded: false
    },
    mounted: function () {
        this.mode = 'list';
        this.getLookupStatus();
        this.getBank();
        this.getPhoneProvider();
        this.getAllSupplier();
    },
    methods: {
        validateBeforeSubmit: function() {
            this.$validator.validateScopes().then(isValid => {
                if (!isValid) return;
                this.errors.clear();
                this.loadingPanel('#supplierCRUDBlock', 'TOGGLE');
                if (this.mode == 'create') {
                    axios.post(route('api.post.supplier.save').url(),
                        new FormData($('#supplierForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        this.loadingPanel('#supplierCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#supplierCRUDBlock', 'TOGGLE');
                    });
                } else if (this.mode == 'edit') {
                    axios.post(route('api.post.supplier.edit', this.supplier.hId).url(),
                        new FormData($('#supplierForm')[0]),
                        { headers: { 'content-type': 'multipart/form-data' } }).then(response => {
                        this.backToList();
                        this.loadingPanel('#supplierCRUDBlock', 'TOGGLE');
                    }).catch(e => {
                        this.handleErrors(e);
                        this.loadingPanel('#supplierCRUDBlock', 'TOGGLE');
                    });
                } else { }
            });
        },
        getAllSupplier: function(page) {
            this.loadingPanel('#supplierListBlock', 'TOGGLE');

            var qS = [];
            if (this.search_supplier_query) { qS.push({ 'key':'s', 'value':this.search_supplier_query }); }
            if (page && typeof(page) == 'number') {
                this.active_page = page;
                qS.push({ 'key':'page', 'value':page });
            }

            axios.get(route('api.get.supplier.read').url() + this.generateQueryStrings(qS)).then(response => {
                this.supplierList = response.data;
                this.loadingPanel('#supplierListBlock', 'TOGGLE');
            }).catch(e => {
                this.handleErrors(e);
                this.loadingPanel('#supplierListBlock', 'TOGGLE');
            });
        },
        createNew: function() {
            this.mode = 'create';
            this.errors.clear();
            this.supplier = this.emptySupplier();
            this.getProduct();
        },
        editSelected: function(idx) {
            this.mode = 'edit';
            this.errors.clear();
            this.supplier = this.supplierList.data[idx];
            this.getProduct();
        },
        showSelected: function(idx) {
            this.mode = 'show';
            this.errors.clear();
            this.supplier = this.supplierList.data[idx];
            this.getProduct();
        },
        deleteSelected: function(idx) {
            axios.post(route('api.post.supplier.delete', idx).url()).then(response => {
                this.backToList();
            }).catch(e => { this.handleErrors(e); });
        },
        backToList: function() {
            this.mode = 'list';
            this.errors.clear();

            if (this.active_page != 0 || this.active_page != 1) {
                this.getAllSupplier(this.active_page);
            } else {
                this.getAllSupplier();
            }
        },
        emptySupplier: function() {
            return {
                hId: '',
                name: '',
                code_sign: '',
                address: '',
                city: '',
                phone_number: '',
                fax_num: '',
                tax_id: '',
                status: '',
                remarks: '',
                payment_due_day: '',
                bank_accounts: [],
                persons_in_charge: [],
                products: [],
                listSelectedProductHId: []
            }
        },
        addNewBankAccount: function() {
            this.supplier.bank_accounts.push({
                bankHId: '',
                account_name: '',
                account_number: '',
                remarks: ''
            });
        },
        removeSelectedBank: function(idx) {
            this.supplier.bank_accounts.splice(idx, 1);
        },
        addNewPIC: function() {
            this.supplier.persons_in_charge.push({
                hId: '',
                first_name: '',
                last_name: '',
                email: '',
                address: '',
                ic_num: '',
                image_filename: '',
                phone_numbers:[{
                    hId: '',
                    phoneProviderHId: '',
                    number: '',
                    remarks: ''
                }]
            });
        },
        removeSelectedPIC: function(idx) {
            this.supplier.persons_in_charge.splice(idx, 1);
        },
        addNewPhone: function(parentIndex) {
            if (!this.supplier.persons_in_charge[parentIndex].hasOwnProperty('phone_numbers')) {
                this.supplier.persons_in_charge[parentIndex].phone_numbers = [];
            }

            this.supplier.persons_in_charge[parentIndex].phone_numbers.push({
                hId: '',
                phoneProviderHId: '',
                number: '',
                remarks: ''
            });
        },
        removeSelectedPhone: function(parentIndex, idx) {
            this.supplier.persons_in_charge[parentIndex].phone_numbers.splice(idx, 1);
        },
        getLookupStatus: function() {
            axios.get(route('api.get.lookup.bycategory', 'STATUS')).then(
                response => { this.statusDDL = response.data; }
            );
        },
        getPhoneProvider: function() {
            axios.get(route('api.get.settings.phone_provider.read').url()).then(
                response => { this.providerDDL = response.data; }
            );
        },
        getBank: function() {
            axios.get(route('api.get.bank.read').url()).then(
                response => { this.bankDDL = response.data; }
            );
        },
        getProduct: function(page) {
            this.tableLoaded = false;
            var qS = [];
            if (page && typeof(page) == 'number') { qS.push({ 'key':'page', 'value':page }); }

            axios.get(route('api.get.product.read').url() + this.generateQueryStrings(qS)).then(
                response => {
                    this.productList = response.data;

                    for (var i = 0; i < this.productList.data.length; i++) {
                        if (_.includes(this.supplier.listSelectedProductHId, this.productList.data[i].hId)) {
                            this.productList.data[i].checked = true;
                        }
                    }

                    this.tableLoaded = true;
                }
            );
        },
        syncToSupplierProd: function(pLIdx) {
            if (this.productList.data[pLIdx].checked) {
                this.supplier.listSelectedProductHId.push(this.productList.data[pLIdx].hId);
            } else {
                _.pull(this.supplier.listSelectedProductHId, this.productList.data[pLIdx].hId);
            }
        }
    },
    watch: {
        mode: function() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                case 'show':
                    this.contentPanel('#supplierListBlock', 'CLOSE')
                    this.contentPanel('#supplierCRUDBlock', 'OPEN')
                    break;
                case 'list':
                default:
                    this.contentPanel('#supplierListBlock', 'OPEN')
                    this.contentPanel('#supplierCRUDBlock', 'CLOSE')
                    break;
            }
        }
    },
    computed: {
        defaultPleaseSelect: function() {
            return '';
        }
    }
});
