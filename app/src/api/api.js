import { url } from "./config";

export async function get(peticion) {
  let respuesta = await fetch(url + peticion);
  if (!respuesta.ok) throw new Error(respuesta.text);

  return respuesta.json();
}

async function call(peticion, datos, method) {
  let respuesta = await fetch(url + peticion, {
    method,
    body: JSON.stringify(datos),
    headers: {
      "Content-Type": "application/json",
    },
  });
  if (!respuesta.ok) throw new Error(respuesta.text);
  return respuesta.json();
}

export async function post(peticion, datos) {
  return call(peticion, datos, "POST");
}

export async function put(peticion, datos) {
  return call(peticion, datos, "PUT");
}

export async function patch(peticion, datos) {
  return call(peticion, datos, "PATCH");
}

export async function del(peticion) {
  return await fetch(url + peticion, {
    method: "DELETE",
  });
}
export async function enviarConArchivos(peticion, datos) {
  let respuesta = await fetch(url + peticion, {
    method: "POST",
    body: datos,
  });
  if (!respuesta.ok) throw new Error(respuesta.text);

  return respuesta.json();
}
