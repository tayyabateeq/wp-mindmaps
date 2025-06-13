import App from "./App";
import { render } from "@wordpress/element";

/**
 * Import the stylesheet for the plugin.
 */
import "./style/main.scss";

// Render the App component into the DOM
// render(<App readOnly={false} />, document.getElementById('mindmap'));
render(
  <App
    singleMindmapEdit={
      document.getElementById("single-mindmap-content") ? true : false
    }
    readOnly={document.getElementById("mindmap") || document.getElementById("single-mindmap-content") ? false : true}
  />,
  document.getElementById("mindmap-edit") || 
  document.getElementById("mindmap-read-only") ||
    document.getElementById("mindmap")
);
