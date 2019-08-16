import React, { Component } from "react";
import { Link } from "react-router-dom";

class PNavMenu extends Component {
  constructor(props) {
    super(props);

    this.state = {
      categories: []
    };
  }

  componentDidMount() {
    fetch(`${this.props.root}categories`)
      .then(res => res.json())
      .then(json => {
        this.setState({
          categories: json.categories
        });
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    return (
      <div>
        <section className="mainCategories">
          {this.state.categories.map((key, i) => (
            <Link
              to={`/product/category/${key.replace(/ /g, "-")}`}
              key={i}
              onClick={this.fetchProductSubCategories}
            >
              {key}
            </Link>
          ))}
        </section>

        <section className="a-switchOptions">
          <Link to={`/store`}>
            <button className="btn a-storeLink a-switchOptions-active">
              Store
            </button>
          </Link>
          <Link to={`/product`}>
            <button className="btn a-productLink a-switchOptions-inactive">
              Products
            </button>
          </Link>
        </section>
      </div>
    );
  }
}

export default PNavMenu;
