export function searchForStore(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json.stores)
    .catch(err => {
      console.log(err);
    });
}

export function fetchStores(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json.stores)
    .catch(err => {
      console.log(err);
    });
}

export function changeVisibility(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json)
    .catch(err => {
      console.log(err);
    });
}

export function deleteStore(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json)
    .catch(err => {
      console.log(err);
    });
}

export function sendMessage(url, data) {
  let formData = new FormData();
  formData.append("subject", data[0]);
  formData.append("body", data[1]);
  return fetch(url, { method: "POST", body: formData })
    .then(res => res.text())
    .then(text => text)
    .catch(err => {
      console.log(err);
    });
}
