<template>
  <v-main>
    <v-row class="pa-5 align-center">
      <v-col>
        <v-btn
          fab
          large
          dark
          color="blue darken-3"
          @click="dialogEjemplo = true"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
      <v-col cols="11">
        <h2 class="font-weight-regular text-center">Sistema de Pensiones</h2>
      </v-col>
    </v-row>
    <v-dialog v-model="dialogEjemplo" persistent scrollable max-width="60vw">
      <v-card>
        <v-card-title class="headline indigo darken-4">
          <span v-if="edit" class="headline" style="color: white">
            Editar Sistema De Pension
          </span>
          <span v-else class="headline" style="color: white">
            Nuevo Sistema de Pension
          </span>
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-row>
              <v-col cols="6">
                <v-text-field
                  v-model="Nombre"
                  :rules="[fieldRules.required]"
                  label="Nombre *"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="Siglas"
                  :rules="[fieldRules.required]"
                  label="Siglas *"
                  prepend-icon="mdi-domain"
                  required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col><label style="color:red;">(*) Campos Obligatorios</label></v-col>
            </v-row>
          </v-form>
          
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            v-if="edit"
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false,edit = false), limpiar()"
            >Cancelar</v-btn>
            <v-btn
            v-else
            color="indigo darken-4"
            text
            @click="(dialogEjemplo = false), limpiar()"
            >Cerrar</v-btn>
          <v-btn
            v-if="edit"
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Modificar</v-btn>
            <v-btn
            v-else
            :loading="saveLoading"
            :disabled="saveLoading"
            color="indigo darken-4"
            class="ma-2 white--text"
            depressed
            @mousedown="validate"
            @click="executeEventClick"
            >Guardar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-row class="justify-center">
      <v-col cols="11">
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
          </v-card-title>
          <v-card-text>
            <v-data-table
              :loading="tableLoading"
              :headers="headers"
              :items="sistemasdepensiones"
              :search="search">
              <template v-slot:[`item`]="{ item }">
                <tr v-bind:class="item.Vigencia?'activo':'desactivo'">
                  <td>{{ item.indice }}</td>
                  <td>{{ item.Nombre }}</td>
                  <td>{{ item.Siglas }}</td>
                  <td>
                    <v-icon class="mr-2" @click="showEditSistemaDePension(item)">mdi-border-color</v-icon>
                    <v-icon v-if="item.Vigencia" class="mr-2" color="red" @click="cambiarVigenciaSistemaDePension(item)">
                      {{item.Vigencia? "mdi-close-circle-outline": "mdi-checkbox-marked-circle-outline"}}
                    </v-icon>
                    <v-icon v-else class="mr-2" color="green" @click="cambiarVigenciaSistemaDePension(item)">
                      {{item.Vigencia? "mdi-close-circle-outline": "mdi-checkbox-marked-circle-outline"}}
                    </v-icon>
                  </td>
                </tr>
              </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-main>
</template>

<script>
import { get, post, patch, put} from "../api/api";
import Swal from "sweetalert2";
//import { Toast } from "../plugins/toast";

export default {
  components: {},
  data() {
    return {
      edit: false,
      valid: true,
      saveLoading: false,
      dialogEjemplo: false,
      tableLoading: true,
      fieldRules: {
        required: (v) => !!v || "Campo requerido",
      },
      headers: [
        {
          text: "N°",
          align: "start",
          sortable: false,
          value: "indice",
          width: "10%",
        },
        { text: "NOMBRE", align: "start", value: "Nombre", width: "50%" },
        { text: "SIGLAS", value: "Siglas", width: "30%" },
        { text: "ACCIONES", value: "actions", width: "20%" },
      ],
      sistemasdepensiones: [],
      search: "",
      Nombre: "",
      Siglas: "",
    };
  },
  methods: {
    fetchData() {
      this.tableLoading = true;
      get("sistemasdepensiones").then((data) => {
        this.tableLoading = false;
        this.sistemasdepensiones = data;
      });
    },
    validate() {
      this.$refs.form.validate();
    },
    limpiar() {
      this.Nombre = "";
      this.Siglas = "";
    },
    executeEventClick() {
      if (this.edit === false) {
        this.registerSistemaDePension();
      } else {
        this.editSistemaDePension();
      }
    },
    assembleSistemaDePension() {
      let form = new FormData();
      form.append("Nombre", this.Nombre);
      form.append("Siglas", this.Siglas);
      return form;
    },

    registerSistemaDePension() {
      if (this.valid == false) return;
      this.saveLoading = true;
      post("sistemasdepensiones", this.assembleSistemaDePension()).then(() => {
        this.saveLoading = false;
        this.dialogEjemplo = false;
        this.fetchData();
        this.limpiar();
        this.actualizarSistemasDePensiones();
        Swal.fire({
            position: "center",
            title: "Sistema",
            text: "Sistema de Pension registrado exitosamente",
            icon: "success",
            confirmButtonText: "Ok",
            timer: 2500,
        });
      }).catch(() => {
          Swal.fire({
              position: "center",
              title: "Sistema",
              text: "Sistema de Pension No Registrado.",
              icon: "error",
              confirmButtonText: "Ok",
              timer: 5000,
          });
          this.saveLoading = false;
          this.dialogEjemplo = false;
        });
    },
    editSistemaDePension() {
      if (this.valid == false) return;
      this.saveLoading = true;
      put("sistemasdepensiones/" + this.editId,this.assembleSistemaDePension()).then(() => {
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.edit = false;
          this.fetchData();
          this.limpiar();
          Swal.fire({
              position: "center",
              title: "Sistema",
              text: "Sistema de Pension actualizado exitosamente",
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
          });
        }).catch(() => {
          Swal.fire({
              position: "center",
              title: "Sistema",
              text: "Sistema de Pension No Actualizado.",
              icon: "error",
              confirmButtonText: "Ok",
              timer: 5000,
          });
          this.saveLoading = false;
          this.editId = null;
          this.dialogEjemplo = false;
          this.edit = false;
          this.fetchData();
          this.limpiar();
        });
    },
    showEditSistemaDePension(sistemadepension) {
      this.edit = true;
      this.editId = sistemadepension.Codigo;
      this.mostrarSistemaDePension(sistemadepension.Codigo).then(() => {
        this.dialogEjemplo = true;
      });
    },
    cambiarVigenciaSistemaDePension(sistemadepension) {
      patch("sistemadepension/" + sistemadepension.Codigo)
        .then((data) => {
          let mensaje = '';
          if(data.Vigencia == 1){
            mensaje = 'Sistema de Pension dado de alta exitosamente';
          }else{
            mensaje = 'Sistema de Pension dado de baja exitosamente';
          }

          this.fetchData();
          this.actualizarSistemasDePensiones();
          Swal.fire({
              position: "center",
              title: "Sistema",
              text: mensaje,
              icon: "success",
              confirmButtonText: "Ok",
              timer: 2500,
          });
        })
        .catch(() => {
          Swal.fire({
              position: "center",
              title: "Sistema",
              text: "Sistema de Pension No Pudo Cambiar De Estado.",
              icon: "error",
              confirmButtonText: "Ok",
              timer: 5000,
          });
        });
    },
    actualizarSistemasDePensiones() {
       get("sistemasdepensiones").then((data) => {
        this.sistemasdepensiones = data;
      });
    },
    async mostrarSistemaDePension(codigo) {
      const sistemadepension = await get("sistemasdepensiones/" + codigo);
      this.Nombre = sistemadepension.Nombre;
      this.Siglas = sistemadepension.Siglas;
    },
  },
  created() {
    this.actualizarSistemasDePensiones();
    this.fetchData();
  },
};
</script>

<style>
  tr.desactivo{
    color: red;
  }
</style>