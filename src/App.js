// import { useLayoutEffect, useState } from "react";
// import {
//   Tldraw,
//   createTLStore,
//   defaultShapeUtils,
//   throttle,
// } from "@tldraw/tldraw";

// import "./styles.css";

// const PERSISTENCE_KEY = "mind-map";

// export default function App() {
//   //[1]
//   const [store] = useState(() =>
//     createTLStore({ shapeUtils: defaultShapeUtils })
//   );
//   //[2]
//   const [loadingState, setLoadingState] = useState({
//     status: "loading",
//   });
//   //[3]
//   useLayoutEffect(() => {
//     setLoadingState({ status: "loading" });

//     // Get persisted data from local storage
//     const persistedSnapshot = localStorage.getItem(PERSISTENCE_KEY);

//     if (persistedSnapshot) {
//       try {
//         const snapshot = JSON.parse(persistedSnapshot);
//         store.loadSnapshot(snapshot);
//         setLoadingState({ status: "ready" });
//       } catch (error) {
//         setLoadingState({ status: "error", error: error.message }); // Something went wrong
//       }
//     } else {
//       setLoadingState({ status: "ready" }); // Nothing persisted, continue with the empty store
//     }

//     // Each time the store changes, run the (debounced) persist function
//     const cleanupFn = store.listen(
//       throttle(() => {
//         const snapshot = store.getSnapshot();
//         localStorage.setItem(PERSISTENCE_KEY, JSON.stringify(snapshot));
//       }, 500)
//     );

//     return () => {
//       cleanupFn();
//     };
//   }, [store]);

//   console.log({ loadingState });

//   // [4]
//   if (loadingState.status === "loading") {
//     return (
//       <div className="tldraw__editor">
//         <h2>Loading...</h2>
//       </div>
//     );
//   }

//   if (loadingState.status === "error") {
//     return (
//       <div className="tldraw__editor">
//         <h2>Error!</h2>
//         <p>{loadingState.error}</p>
//       </div>
//     );
//   }
//   return (
//     <div style={{ height: "600px" }}>
//       <Tldraw store={store} />
//     </div>
//   );
// }

// import {
//   Editor,
//   Tldraw,
//   createTLStore,
//   defaultShapeUtils,
//   throttle,
// } from "@tldraw/tldraw";
// import { useLayoutEffect, useState } from "react";
// import "./styles.css";

// const PERSISTENCE_KEY = "mind-map";

// export default function App() {
//   //[1]
//   const [store] = useState(() =>
//     createTLStore({ shapeUtils: defaultShapeUtils })
//   );
//   //[2]
//   const [loadingState, setLoadingState] = useState({
//     status: "loading",
//   });
//   //[3]

//   useLayoutEffect(() => {
//     setLoadingState({ status: "loading" });

//     // Get persisted data from local storage
//     const persistedSnapshot = localStorage.getItem(PERSISTENCE_KEY);

//     if (persistedSnapshot) {
//       try {
//         const snapshot = JSON.parse(persistedSnapshot);
//         store.loadSnapshot(snapshot);
//         setLoadingState({ status: "ready" });
//       } catch (error) {
//         setLoadingState({ status: "error", error: error.message }); // Something went wrong
//       }
//     } else {
//       setLoadingState({ status: "ready" }); // Nothing persisted, continue with the empty store
//     }

//     // Each time the store changes, run the (debounced) persist function
//     const cleanupFn = store.listen(
//       throttle(() => {
//         const snapshot = store.getSnapshot();
//         const editor = new Editor({
//           store,
//           shapeUtils: defaultShapeUtils,
//           tools: [],
//         });

//         const shapes = editor.getCurrentPageShapes();
//         if (shapes.length > 0) {
//           const svg = editor.getSvg(shapes);

//           svg.then((res) => {
//             if (res) {
//               localStorage.setItem("mind-map-svg", res?.outerHTML);
//             }
//           });
//         }

//         localStorage.setItem(PERSISTENCE_KEY, JSON.stringify(snapshot));
//       }, 500)
//     );

//     return () => {
//       cleanupFn();
//     };
//   }, [store]);

//   // [4]
//   if (loadingState.status === "loading") {
//     return (
//       <div className="tldraw__editor">
//         <h2>Loading...</h2>
//       </div>
//     );
//   }

//   if (loadingState.status === "error") {
//     return (
//       <div className="tldraw__editor">
//         <h2>Error!</h2>
//         <p>{loadingState.error}</p>
//       </div>
//     );
//   }
//   return (
//     <div style={{ height: "600px" }}>
//       <Tldraw store={store} />
//     </div>
//   );
// }

import { useEffect, useState } from "react";
import {
  //  Editor,
  Tldraw,
  createTLStore,
  defaultShapeUtils,
  throttle,
} from "@tldraw/tldraw";
import "./styles.css";

const PERSISTENCE_KEY = "mind-map";

export default function App({ readOnly, singleMindmapEdit }) {
  const [store] = useState(() =>
    createTLStore({ shapeUtils: defaultShapeUtils })
  );

  const [loadingState, setLoadingState] = useState({
    status: "loading",
  });

  useEffect(() => {
    setLoadingState({ status: "loading" });

    const persistedSnapshot = localStorage.getItem(PERSISTENCE_KEY);

    if (persistedSnapshot) {
      try {
        const snapshot = JSON.parse(persistedSnapshot);
        store.loadSnapshot(snapshot);
        setLoadingState({ status: "ready" });
      } catch (error) {
        setLoadingState({ status: "error", error: error.message });
      }
    } else {
      setLoadingState({ status: "ready" });
    }

    const cleanupFn = store.listen(
      throttle(() => {
        const snapshot = store.getSnapshot();
        // const editor = new Editor({
        //   store,
        //   shapeUtils: defaultShapeUtils,
        //   tools: [],
        // });

        // const shapes = editor.getCurrentPageShapes();
        // if (shapes.length > 0) {
        //   const svg = editor.getSvg(shapes);

        //   svg.then((res) => {
        //     if (res) {
        //       localStorage.setItem("mind-map-svg", res?.outerHTML);
        //     }
        //   });
        // }
        // localStorage.setItem(PERSISTENCE_KEY, JSON.stringify(snapshot));
      }, 500)
    );

    return () => {
      cleanupFn();
    };
  }, [store]);
  useEffect(() => {
    console.log("use effect");
    if (readOnly) {
      const mindMapData = document.getElementById("mindmap-tldraw").textContent;
      console.log({ mindMapData });
      try {
        const parsedData = JSON.parse(mindMapData);
        store.loadSnapshot(parsedData);
        setLoadingState({ status: "ready" });
      } catch (error) {
        console.log("err", { error });
        setLoadingState({ status: "error", error: error.message });
      }
    }
  }, [readOnly, store]);
  useEffect(() => {
    if (singleMindmapEdit) {
      const mindMapData = document.getElementById(
        "single-mindmap-content"
      ).textContent;
      console.log({ mindMapData });
      try {
        const parsedData = JSON.parse(mindMapData);
        store.loadSnapshot(parsedData);
        setLoadingState({ status: "ready" });
        // const containerElement = document.getElementById(
        //   "single-mindmap-content"
        // );

        // // Check if the element exists
        // if (containerElement) {
        //   // Get the <p> tag inside the container
        //   const pElement = containerElement.querySelector("p");

        //   // Check if the <p> tag exists
        //   if (pElement) {
        //     // Get the content of the <p> tag
        //     const mindMapData = pElement.textContent || pElement.innerText;
        //     console.log({ mindMapData });
        //     const parsedData = JSON.parse(mindMapData);
        //     store.loadSnapshot(parsedData);
        //     setLoadingState({ status: "ready" });
        //   } else {
        //     console.error("No <p> tag found inside the container.");
        //   }
        // } else {
        //   console.error("Element with id 'single-mind' not found.");
        // }
      } catch (error) {
        console.log("err", { error });
        setLoadingState({ status: "error", error: error.message });
      }
    }
  }, []);
  // Update the saveDrawingAndCreatePost function to save drawing and other information
  const saveDrawingAndCreatePost = () => {
    // const mindMap = localStorage.getItem("mind-map");
    const snapshot = store.getSnapshot();

    if (snapshot) {
      const title = document.getElementById("mindmap_title").value;
      const description = document.getElementById("mindmap_description").value;
      const tags = document.getElementById("mindmap_tags").value;
      const category = document.getElementById("mindmap_category").value;
      // console.log("mindmap-data", mindMap );
      // Check if any required field is empty or if snapshot is empty
      if (!title || !description || !tags || !category || !snapshot) {
        alert("Please fill all fields and create a mindmap before saving.");
        return;
      }
      jQuery.ajax({
        url: ajax_object.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
          action: "save_drawing_and_create_post",
          nonce: ajax_object.nonce,
          drawingData: JSON.stringify(snapshot),
          mindmap_title: title,
          mindmap_description: description,
          mindmap_tags: tags,
          mindmap_category: category,
        },
        success: function (response) {
          console.log("Success:", response);
          localStorage.removeItem("mind-map");
          alert("Successfully created post");
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    } else {
      console.error("No SVG data found in local storage.");
    }
  };
  const UpdateTLDraw = () => {
    const snapshot = store.getSnapshot();
    const postId = post_id_object.postId; // Access post ID from post_id_object

    if (snapshot) {
      jQuery.ajax({
        url: ajax_object.ajax_url,
        type: "POST",
        dataType: "json",
        data: {
          action: "update_drawing",
          drawingData: JSON.stringify(snapshot),
          post_id: postId,
        },
        success: function (response) {
          console.log("Success:", response);
          alert("TLDraw updated successfully");
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    } else {
      console.error("No SVG data found in local storage.");
    }
  };
  if (loadingState.status === "loading") {
    return (
      <div className="tldraw__editor">
        <h2>Loading...</h2>
      </div>
    );
  }

  if (loadingState.status === "error") {
    return (
      <div className="tldraw__editor">
        <h2>Error!</h2>
        <p>{loadingState.error}</p>
      </div>
    );
  }

  return (
    <div style={{ height: "600px", marginTop: "40px", marginBottom: "100px" }}>
      <Tldraw
        store={store}
        onMount={(editor) => {
          editor.updateInstanceState({ isReadonly: readOnly });
        }}
      />
      {!readOnly && !singleMindmapEdit && (
        <button
          onClick={saveDrawingAndCreatePost}
          style={{
            marginTop: "40px",
            backgroundColor: "#007bff",
            color: "#ffffff",
            padding: "10px 20px",
            border: "none",
            borderRadius: "5px",
            cursor: "pointer",
            width: "250px",
          }}
        >
          Save Mindmap
        </button>
      )}
      {!readOnly && singleMindmapEdit && (
        <button
          onClick={UpdateTLDraw}
          style={{
            marginTop: "40px",
            backgroundColor: "#059862",
            color: "#ffffff",
            padding: "10px 20px",
            border: "none",
            borderRadius: "5px",
            cursor: "pointer",
            width: "250px",
          }}
        >
          Update Mindmap
        </button>
      )}
    </div>
  );
}
