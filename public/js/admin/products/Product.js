import React, { Component } from "react";
import { fetchProducts } from "../controllers/productController";
import ProductTable from "./ProductTable";

class Products extends Component {
  constructor(props) {
    super(props);

    this.state = {
      products: []
    };
  }

  componentWillMount() {
    fetchProducts(`${this.props.root}product`).then(res => {
      this.setState({
        products: res.products
      });
    });
  }

  render() {
    return (
      <ProductTable products={this.state.products} root={this.props.root} />
    );
  }
}

export default Products;
