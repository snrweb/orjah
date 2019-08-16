import React, { Component } from "react";
import { fetchStores } from "../controllers/storeController";
import StoreTable from "./StoreTable";

class Stores extends Component {
  constructor(props) {
    super(props);

    this.state = {
      stores: []
    };
  }

  componentWillMount() {
    fetchStores(`${this.props.root}store`).then(res => {
      this.setState({ stores: res });
    });
  }

  render() {
    return <StoreTable stores={this.state.stores} root={this.props.root} />;
  }
}

export default Stores;
