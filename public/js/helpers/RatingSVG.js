import React, { Component } from "react";

import FullStar from "./../../images/svg/full_star.svg";
import HalfStar from "./../../images/svg/half_star.svg";
import NoStar from "./../../images/svg/no_star.svg";

class RatingSVG extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <React.Fragment>
        {this.props.rate == 0 && (
          <React.Fragment>
            <NoStar />
            <NoStar />
            <NoStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}

        {this.props.rate >= 0.5 && this.props.rate < 1 && (
          <React.Fragment>
            <HalfStar />
            <NoStar />
            <NoStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 1 && this.props.rate < 1.5 && (
          <React.Fragment>
            <FullStar />
            <NoStar />
            <NoStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 1.5 && this.props.rate < 2 && (
          <React.Fragment>
            <FullStar />
            <HalfStar />
            <NoStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 2 && this.props.rate < 2.5 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <NoStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 2.5 && this.props.rate < 3 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <HalfStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 3 && this.props.rate < 3.5 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <FullStar />
            <NoStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 3.5 && this.props.rate < 4 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <FullStar />
            <HalfStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 4 && this.props.rate < 4.5 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <FullStar />
            <FullStar />
            <NoStar />
          </React.Fragment>
        )}
        {this.props.rate >= 4.5 && this.props.rate < 5 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <FullStar />
            <FullStar />
            <HalfStar />
          </React.Fragment>
        )}
        {this.props.rate == 5 && (
          <React.Fragment>
            <FullStar />
            <FullStar />
            <FullStar />
            <FullStar />
            <FullStar />
          </React.Fragment>
        )}
      </React.Fragment>
    );
  }
}

export default RatingSVG;
