const standardRoomSelector = document.querySelector('#standard-room');
const executiveRoomSelector = document.querySelector('#executive-room');
const kingRoomSelector = document.querySelector('#king-room');

if (standardRoomSelector && executiveRoomSelector && kingRoomSelector) {
  standardRoomSelector.addEventListener('click', () => {
    standardRoomSelector.classList.add('active-header');
    executiveRoomSelector.classList.remove('active-header');
    kingRoomSelector.classList.remove('active-header');
  });

  executiveRoomSelector.addEventListener('click', () => {
    executiveRoomSelector.classList.add('active-header');
    standardRoomSelector.classList.remove('active-header');
    kingRoomSelector.classList.remove('active-header');
  });

  kingRoomSelector.addEventListener('click', () => {
    kingRoomSelector.classList.add('active-header');
    standardRoomSelector.classList.remove('active-header');
    executiveRoomSelector.classList.remove('active-header');
  });
}