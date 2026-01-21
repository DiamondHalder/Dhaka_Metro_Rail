document.addEventListener('DOMContentLoaded', function() {
    const fromStation = document.getElementById('fromStation');
    const toStation = document.getElementById('toStation');
    const fareAmount = document.getElementById('fareAmount');
    const buyTicketBtn = document.getElementById('buyTicketBtn');
    const successModal = document.getElementById('successModal');

    
    function updateFare() {
        const from = fromStation.value;
        const to = toStation.value;

        if (from && to) {
            if (from === to) {
                fareAmount.innerText = "৳ 0.00";
                return;
            }

            fetch('get_fare.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`
            })
            .then(response => response.text())
            .then(data => {
                fareAmount.innerText = data;
            })
            .catch(error => {
                console.error('Error fetching fare:', error);
                fareAmount.innerText = "৳ --";
            });
        }
    }

   
    if (fromStation) fromStation.addEventListener('change', updateFare);
    if (toStation) toStation.addEventListener('change', updateFare);

    
    if (buyTicketBtn) {
        buyTicketBtn.addEventListener('click', function() {
            const from = fromStation.value;
            const to = toStation.value;

            
            if (from && to && from !== to) {
                
               
                buyTicketBtn.innerText = "PROCESSING...";
                buyTicketBtn.disabled = true;

                
                fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `from_st=${encodeURIComponent(from)}&to_st=${encodeURIComponent(to)}`
                })
                .then(response => response.json()) 
                .then(data => {
                    if (data.success) {
                       
                        const ticketDisplay = document.querySelector('#successModal p.font-mono');
                        if (ticketDisplay) {
                            ticketDisplay.innerText = `#MRT-${data.ticket_id}`;
                        }
                        
                        
                        successModal.classList.remove('hidden');
                        successModal.classList.add('flex');
                    } else {
                        alert("Booking Failed: " + (data.message || "Unknown error"));
                        
                        buyTicketBtn.innerText = "BUY TICKET NOW";
                        buyTicketBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error processing booking:', error);
                    alert("An error occurred. Please check database connection.");
                    buyTicketBtn.innerText = "BUY TICKET NOW";
                    buyTicketBtn.disabled = false;
                });

            } else {
                alert("Please select different source and destination stations.");
            }
        });
    }
});


function closeModal() {
    const successModal = document.getElementById('successModal');
    if (successModal) {
        successModal.classList.add('hidden');
        successModal.classList.remove('flex');
    }
    location.reload(); 
}