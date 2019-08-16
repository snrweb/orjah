import React, { Component } from "react";
import StoreTable from "./StoreTable";
import { fetchStores } from "../controllers/storeController";

class StoreCat extends Component {
  constructor(props) {
    super(props);
    this.state = {
      storeCat: []
    };
  }

  componentWillMount() {
    const { cat } = this.props.match.params;
    fetchStores(`${this.props.root}store/category/${cat}`).then(res => {
      this.setState({ storeCat: res });
    });
  }

  componentWillUpdate(nextProp) {
    if (this.props.match.params.cat !== nextProp.match.params.cat) {
      const { cat } = nextProp.match.params;
      fetchStores(`${this.props.root}store/category/${cat}`).then(res => {
        this.setState({ storeCat: res });
      });
    }
  }

  render() {
    return <StoreTable stores={this.state.storeCat} root={this.props.root} />;
  }
}

export default StoreCat;
