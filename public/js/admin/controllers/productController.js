export function searchForProduct(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json.products)
    .catch(err => {
      console.log(err);
    });
}

export function fetchProducts(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json)
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

export function deleteProduct(url) {
  return fetch(url)
    .then(res => res.json())
    .then(json => json)
    .catch(err => {
      console.log(err);
    });
}
