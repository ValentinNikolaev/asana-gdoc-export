<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';
?>
<html>
<head>
    <script type="text/javascript">
        var CLIENT_ID = '<?= OA2_CLIENT_ID;?>';
        var SCOPES = <?= JS_SCOPES;?>

            /**
             * Check if current user has authorized this application.
             */
                function checkAuth() {
                gapi.auth.authorize(
                    {
                        'client_id': CLIENT_ID,
                        'scope': SCOPES,
                        'immediate': true
                    }, handleAuthResult);
            }

        /**
         * Handle response from authorization server.
         *
         * @param {Object} authResult Authorization result.
         */
        function handleAuthResult(authResult) {
            var authorizeDiv = document.getElementById('authorize-div');
            if (authResult && !authResult.error) {
                // Hide auth UI, then load client library.
                authorizeDiv.style.display = 'none';
                loadDriveApi();
            } else {
                // Show auth UI, allowing the user to initiate authorization by
                // clicking authorize button.
                authorizeDiv.style.display = 'inline';
            }
        }

        /**
         * Initiate auth flow in response to user clicking authorize button.
         *
         * @param {Event} event Button click event.
         */
        function handleAuthClick(event) {
            gapi.auth.authorize(
                {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
                handleAuthResult);
            return false;
        }

        /**
         * Load Drive API client library.
         */
        function loadDriveApi() {
            gapi.client.load('drive', 'v2', listFiles);
        }

        /**
         * Print files.
         */
        function listFiles() {
            var request = gapi.client.drive.files.list({
                'q': '(title = "<?=GDOC_REPORT_DIR_NAME;?>" or title contains "<?=date(DATE_FORMAT_FNAME);?>")' +
                ' and trashed = false and (mimeType="<?=GDOC_SHEET_MIME;?>" or mimeType="<?=GDOC_FOLDER_MIME;?>")'
//                'maxResults': 10
            });

            request.execute(function(resp) {
                appendPre('Files:');
                var files = resp.items;
                if (files && files.length > 0) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        appendPre(file.title + ' (' + file.id + ')');
                    }
                } else {
                    appendPre('No files found.');
                }
            });
        }


        /**
        * Print folders.
        */
        function listFolders() {
            var request = gapi.client.drive.files.list({
                'q': 'title = "<?=GDOC_REPORT_DIR_NAME;?>" and trashed = false and mimeType="application/vnd.google-apps.folder"'
//                'maxResults': 10
            });

            request.execute(function(resp) {
                appendPre('Files:');
                var files = resp.items;
                if (files && files.length > 0) {
                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        appendPre(file.title + ' (' + file.id + ')');
                    }
                } else {
                    appendPre('No files found.');
                }
            });
        }

        /**
         * Retrieve a list of File resources.
         *
         * @param {Function} callback Function to call when the request is complete.
         */
        function retrieveAllFiles(callback) {
            var retrievePageOfFiles = function(request, result) {
                request.execute(function(resp) {
                    result = result.concat(resp.items);
                    var nextPageToken = resp.nextPageToken;
                    if (nextPageToken) {
                        request = gapi.client.drive.files.list({
                            'pageToken': nextPageToken
                        });
                        retrievePageOfFiles(request, result);
                    } else {
                        callback(result);
                    }
                });
            }
            var initialRequest = gapi.client.drive.files.list();
            retrievePageOfFiles(initialRequest, []);
        }

        /**
         * Append a pre element to the body containing the given message
         * as its text node.
         *
         * @param {string} message Text to be placed in pre element.
         */
        function appendPre(message) {
            var pre = document.getElementById('output');
            var textContent = document.createTextNode(message + '\n');
            pre.appendChild(textContent);
        }

    </script>
    <script src="https://apis.google.com/js/client.js?onload=checkAuth">
    </script>
</head>
<body>
<div id="authorize-div" style="display: none">
    <span>Authorize access to Drive API</span>
    <!--Button for the user to click to initiate auth sequence -->
    <button id="authorize-button" onclick="handleAuthClick(event)">
        Authorize
    </button>
</div>
<pre id="output"></pre>
</body>