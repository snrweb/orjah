import React, { Component } from "react";
import { Link } from "react-router-dom";
import { fetchProducts } from "../controllers/productController";
import ProductTable from "./ProductTable";

class ProductSubCat extends Component {
  constructor(props) {
    super(props);

    this.state = {
      products: [],
      productSubCats: [],
      currentCats: ""
    };
  }

  componentWillMount() {
    const { cat, subCat } = this.props.match.params;
    fetchProducts(`${this.props.root}product/category/${cat}/${subCat}`).then(
      res => {
        this.setState({
          products: res.products,
          currentCats: cat,
          productSubCats: res.subCats
        });
      }
    );
  }

  componentWillUpdate(nextProp) {
    if (this.props.match.params.subCat !== nextProp.match.params.subCat) {
      const { cat, subCat } = nextProp.match.params;
      fetchProducts(`${this.props.root}product/category/${cat}/${subCat}`).then(
        res => {
          this.setState({
            products: res.products,
            currentCats: cat,
            productSubCats: res.subCats
          });
        }
      );
    }
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

export default ProductSubCat;
