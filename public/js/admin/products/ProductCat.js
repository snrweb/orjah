import React, { Component } from "react";
import { Link } from "react-router-dom";
import { fetchProducts } from "../controllers/productController";
import ProductTable from "./ProductTable";

class ProductCat extends Component {
  constructor(props) {
    super(props);

    this.state = {
      products: [],
      productSubCats: [],
      currentCats: ""
    };
  }

  componentWillUpdate(nextProp) {
    if (this.props.match.params.cat !== nextProp.match.params.cat) {
      const { cat } = nextProp.match.params;
      fetchProducts(`${this.props.root}product/category/${cat}`).then(res => {
        this.setState({
          products: res.products,
          currentCats: cat,
          productSubCats: res.subCats
        });
      });
    }
  }

  componentWillMount() {
    const { cat } = this.props.match.params;
    fetchProducts(`${this.props.root}product/category/${cat}`).then(res => {
      this.setState({
        products: res.products,
        currentCats: cat,
        productSubCats: res.subCats
      });
    });
  }

  render() {
    return (
      <React.Fragment>
        <section className="a-productSubCategories-menu">
          {this.state.productSubCats.map((subCat, i) => (
            <Link
              to={`${this.state.currentCats}/${subCat.replace(/ /g, "-")}`}
              key={i}
            >
              {subCat}
            </Link>
          ))}
        </section>

        <ProductTable products={this.state.products} root={this.props.root} />
      </React.Fragment>
    );
  }
}

export default ProductCat;
