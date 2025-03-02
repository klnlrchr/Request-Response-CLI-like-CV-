            document.addEventListener('DOMContentLoaded', () => {
                const input = document.getElementById('command-input');
                const cursor = document.getElementById('cursor');
                const terminal = document.getElementById('terminal');

                input.addEventListener('keydown', (event) => {
                    const commandLine = cursor.previousSibling;

                    if (event.key === 'Enter') {
                        const command = commandLine ? commandLine.textContent.trim() : '';

                        if (command !== "") {
                            // Send command
                            fetch('command.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ command }) // Send command
                            })
                                .then(() => {
                                    // Fetch resposne
                                    return fetch('responses.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ command }) // Send command again
                                    });
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.response) {
                                        // Show command and response
                                        terminal.innerHTML += `<div>${command}</div><div>${data.response}</div>`;
                                    } else {
                                        // Show error
                                        terminal.innerHTML += `<div>${command}</div><div>Error: ${data.error}</div>`;
                                    }
                                    terminal.scrollTop = terminal.scrollHeight; // Scroll down
                                })
                                .catch(err => {
                                    // Show network error
                                    terminal.innerHTML += `<div>${command}</div><div>Request failed: ${err}</div>`;
                                    terminal.scrollTop = terminal.scrollHeight;
                                });
                        } else {
                            terminal.innerHTML += `<div>Error: Command is empty.</div>`;
                        }

                        // Reset line
                        if (commandLine) {
                            commandLine.textContent = '';
                        }
                    } else if (event.key === 'Backspace') {
                        // Backspace: Delete last character
                        if (commandLine && commandLine.textContent.length > 0) {
                            commandLine.textContent = commandLine.textContent.slice(0, -1);
                        }
                    } else if (event.key.length === 1) {
                            if (commandLine) {
                            commandLine.textContent += event.key;
                        }
                    }

                    // Standardaction prevent
                    event.preventDefault();
                });

                // Focus on line
                document.body.addEventListener('click', () => input.focus());

                
            });

