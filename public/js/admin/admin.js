import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route } from "react-router-dom";

import { ROOT, UROOT, NAROOT } from "./config/config";
import GenNavBar from "./components/GenNavBar";
import NavMenu from "./components/NavMenu";
import PNavMenu from "./components/PNavMenu";
import StoreCat from "./stores/StoreCat";
import Stores from "./stores/Stores";
import Product from "./products/Product";
import ProductCat from "./products/ProductCat";
import ProductSubCat from "./products/ProductSubCat";
import Terms from "./Terms";
import About from "./About";

ReactDOM.render(
  <Router basename="/orjah/admin223/">
    <Route path="/" render={() => <GenNavBar root={ROOT} uroot={UROOT} />} />
    <Route exact path="/">
      <Route exact path="/" render={() => <NavMenu root={ROOT} />} />
      <Route exact path="/" render={() => <Stores root={ROOT} />} />
    </Route>

    <Route path={`/store`}>
      <Route path={`/store`} render={() => <NavMenu root={ROOT} />} />
      <Route exact path={`/store`} render={() => <Stores root={ROOT} />} />

      <Route
        exact
        path={`/store/category/:cat`}
        render={props => <StoreCat {...props} root={ROOT} />}
      />
    </Route>

    <Route path={`/product`}>
      <Route path={`/product`} render={() => <PNavMenu root={ROOT} />} />

      <Route exact path={`/product`} render={() => <Product root={ROOT} />} />

      <Route
        exact
        path={`/product/category/:cat`}
        render={props => <ProductCat {...props} root={ROOT} />}
      />

      <Route
        exact
        path={`/product/category/:cat/:subCat`}
        render={props => <ProductSubCat {...props} root={ROOT} />}
      />
    </Route>

    <Route
      exact
      path={`/editAbout`}
      render={props => <About {...props} root={ROOT} />}
    />

    <Route
      exact
      path={`/editTerms`}
      render={props => <Terms {...props} root={ROOT} />}
    />
  </Router>,
  document.getElementById("root")
);
