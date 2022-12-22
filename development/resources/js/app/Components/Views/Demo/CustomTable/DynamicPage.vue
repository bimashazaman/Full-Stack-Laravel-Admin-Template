<template>
  <div class="p-3">
    <div class="row">
      <div class="col d-flex justify-content-end">
        <button type="button" class="btn btn-primary btn-with-shadow" data-toggle="modal"
                @click.prevent="openModal()">
          Add {{ customTable.name }}
        </button>
      </div>
    </div>
    <div class="row pt-3">
      <div class="col">
        <app-table
          :id="tableId"
          :options="options"
          @action="getAction"
          @getRows="getSelectedRows"
        />
      </div>
    </div>
    <add-edit-modal
      v-if="isAddEditModal"
      :selected-url="selectedUrl"
      :table-data="customTable"
      :table-id="tableId"
      @close-modal="closeModal"/>

    <app-confirmation-modal
      v-if="confirmationModalActive"
      modal-id="table-row-delete-modal"
      :loading="loading"
      @confirmed="confirmed"
      @cancelled="closeModal"/>
  </div>
</template>

<script>
import AddEditModal from './AddEditModal.vue'
import {axiosDelete} from "../../../../Helpers/AxiosHelper";
import {settingsDateFormat} from "../../../../Helpers/DateTimeHelper";

export default {
  props: ['customTable'],
  components: {AddEditModal},
  data() {
    return {
      loading: false,
      tableId: 'dynamic-tab-component-datatable-' + this.customTable.id,
      selectedUrl: '',
      isAddEditModal: false,
      confirmationModalActive: false,
      options: {
        showAction: true,
        responsive: true,
        dataKey: "data",
        url: `admin/app/custom-table/values/${this.customTable.id}`,
        tableShadow: false,
        showSearch: false,
        showFilter: false,
        name: "dynamic-tab-component-datatable",
        rowLimit: 10,
        paginationType: "pagination",
        actionType: "dropdown",
        columns: [
          ...this.customTable.fields.map(col => {
            return {
              title: col.name,
              type: "object",
              key: 'field_values',
              modifier: (value, row) => {
                if(col.custom_field_type?.name == 'date'){
                  let date = value.find((field) => field.custom_field_id == col.id)?.value
                  return settingsDateFormat(date)
                }
                return value.find((field) => field.custom_field_id == col.id)?.value
              },
              isVisible: true,
            }
          }),
          {
            title: this.$t('actions'),
            type: 'action'
          }
        ],
        actions: [
          {
            title: 'Edit',
            icon: 'edit',
            type: 'edit',
          },
          {
            title: 'Delete',
            icon: 'trash',
            type: 'delete',
          },
        ]

      }
    }
  },
  methods: {
    openModal() {
      this.isAddEditModal = true;
      $(`#dynamic-tab-add-edit-modal-${this.customTable.id}`).modal('show');
    },
    confirmed() {
      this.loading = true;
      axiosDelete(this.selectedUrl).then(response => {
        this.$toastr.s(response.data.message);
        this.$hub.$emit('reload-' + this.tableId);
      }).catch(({error}) => {
        this.$toastr.e(response.data.message);
      }).finally(() => {
        this.loading = false;
        this.selectedUrl = '';
        this.confirmationModalActive = false;
        $(`#table-row-delete-modal`).modal('hide');
      });
    },
    getSelectedRows(e) {
      console.log(e);
    },
    closeModal() {
      this.isAddEditModal = false
      $(`#dynamic-tab-add-edit-modal-${this.customTable.id}`).modal('hide');
      this.selectedUrl = '';
    },
    getAction(rowData, actionObj) {
      this.selectedUrl = `admin/app/custom-table/row/${rowData.id}`;
      if (actionObj.type === 'edit') {
        this.isAddEditModal = true;
      }
      if (actionObj.type === "delete") {
        this.confirmationModalActive = true;
      }
    },
  },
}
</script>

<style>


</style>