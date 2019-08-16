import React, { Component } from "react";
import {
  searchForProduct,
  changeVisibility,
  deleteProduct
} from "../controllers/productController";

class ProductTable extends Component {
  constructor(props) {
    super(props);

    this.state = {
      products: []
    };

    this.searchProduct = this.searchProduct.bind(this);
    this.toggleVisibility = this.toggleVisibility.bind(this);
    this.delete = this.delete.bind(this);
  }

  searchProduct(e) {
    let searchValue = e.target.value;
    searchForProduct(`${this.props.root}searchProduct/${searchValue}`).then(
      res => {
        this.setState({
          products: res
        });
      }
    );
  }

  toggleVisibility(product_id) {
    let btn = document.querySelector(`#softDelete${product_id}`);
    let visibilityType = btn.innerHTML == "Off" ? 0 : 1;
    changeVisibility(
      `${this.props.root}productSoftDelete/${product_id}/${visibilityType}`
    ).then(res => {
      if (res == 1) {
        btn.classList.remove("a-tableVisibiltyOn");
        btn.classList.add("a-tableVisibiltyOff");
        btn.innerHTML = "Off";
      } else {
        btn.classList.remove("a-tableVisibiltyOff");
        btn.classList.add("a-tableVisibiltyOn");
        btn.innerHTML = "On";
      }
    });
  }

  delete(product_id) {
    if (confirm("Are you sure you want to delete this product?")) {
      deleteProduct(`${this.props.root}deleteProduct/${product_id}`).then(
        res => {
          if (res) {
            document.querySelector(`#row${product_id}`).style.display = "none";
          }
        }
      );
    }
  }

  render() {
    return (
      <section>
        <div className="a-search-input">
          <input
            type="text"
            placeholder="Search products by name or ID"
            onChange={this.searchProduct}
          />
        </div>
        <table className="a-table">
          <thead>
            <tr>
              <td>Product Name</td>
              <td>Category</td>
              <td>Price</td>
              <td>Details</td>
              <td>Rating</td>
              <td>Time Uploaded</td>
              <td>Visibility</td>
              <td>Delete</td>
            </tr>
          </thead>
          <tbody>
            {this.state.products == ""
              ? this.props.products.map(p => (
                  <tr key={p.product_id} id={`row${p.product_id}`}>
                    <td>{p.product_name}</td>
                    <td>{p.product_cat}</td>
                    <td>{p.product_price}</td>
                    <td>{p.product_details}</td>
                    <td>{p.product_rating}</td>
                    <td>{p.time_uploaded}</td>
                    <td>
                      <button
                        className={
                          p.deleted == 0
                            ? `btn a-tableVisibiltyOn`
                            : `btn a-tableVisibiltyOff`
                        }
                        id={`softDelete${p.product_id}`}
                        onClick={() => this.toggleVisibility(p.product_id)}
                      >
                        {p.deleted == 0 ? `On` : `Off`}
                      </button>
                    </td>

                    <td>
                      <button
                        className="btn a-tableDelete"
                        id={`delete${p.product_id}`}
                        onClick={() => this.delete(p.product_id)}
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))
              : this.state.products.map(p => (
                  <tr key={p.product_id} id={`row${p.product_id}`}>
                    <td>{p.product_name}</td>
                    <td>{p.product_cat}</td>
                    <td>{p.product_price}</td>
                    <td>{p.product_details}</td>
                    <td>{p.product_rating}</td>
                    <td>{p.time_uploaded}</td>
                    <td>
                      <button
                        className={
                          p.deleted == 0
                            ? `btn a-tableVisibiltyOn`
                            : `btn a-tableVisibiltyOff`
                        }
                        id={`softDelete${p.product_id}`}
                        onClick={() => this.toggleVisibility(p.product_id)}
                      >
                        {p.deleted == 0 ? `On` : `Off`}
                      </button>
                    </td>

                    <td>
                      <button
                        className="btn a-tableDelete"
                        id={`delete${p.product_id}`}
                        onClick={() => this.delete(p.product_id)}
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
          </tbody>
        </table>
      </section>
    );
  }
}

export default ProductTable;
