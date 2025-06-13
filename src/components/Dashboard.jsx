// import React from "react";

// import { Tldraw } from "@tldraw/tldraw";

// const Dashboard = () => {
//   return (
//     <div style={{ position: "relative", inset: 0, height: "500px" }}>
//       <Tldraw
//         onChange={(data) => {
          
//         }}
//         showUI
//         showMenu
//         showMultiplayerMenu
//         showPages
//         showStyles
//         showZoom
//         showTools

//       />
//     </div>
//   );
// };

// export default Dashboard;
import React, { useState } from "react";
import { Tldraw } from "@tldraw/tldraw";
import flatted from "flatted";

const Dashboard = () => {
  const [drawingData, setDrawingData] = useState(null);

  const handleDrawingChange = (data) => {
    // Update the local state with the drawing data
    setDrawingData(data);
  };

  const saveDrawing = () => {
    // Use AJAX to send the drawing data to the server
    const ajaxUrl = ajax_object.ajax_url;

    const data = {
        action: "save_drawing",
        drawingData: drawingData, // Directly pass the drawing data
        // security: ajax_object.mindmaps_nonce, // Include the nonce for security
        postTitle: "Your Post Title", // Replace with the desired post title
        postContent: "Your Post Content",
        postId: 45,
    };

    // Send the data to the server
    fetch(ajaxUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams(data).toString(),
    })
    .then((response) => response.json())
    .then((result) => {
        console.log(result);
        // Handle the server response if needed
    })
    .catch((error) => {
        console.error("Error:", error);
    });
};


  return (
    <div style={{ position: "relative", inset: 0, height: "500px" }}>
      <Tldraw
        onChange={handleDrawingChange}
        showUI
        showMenu
        showMultiplayerMenu
        showPages
        showStyles
        showZoom
        showTools
      />
      <button onClick={saveDrawing} style={{ position: "absolute", zIndex: "2" }}>Save Drawing</button>
    </div>
  );
};

export default Dashboard;