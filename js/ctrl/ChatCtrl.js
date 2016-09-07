var demo = angular.module("mjChat", []);
demo.controller("ChatCtrl", function($scope, $http) {
	$scope.url = 'data/conn_manager.php';
	$scope.loggedInUserId = "1";
	$scope.send = function() {
		$http.post($scope.url, {
			"senderId" : $scope.loggedInUserId,
			"receiverId" : $scope.selecteduser.userid,
			"msgContent" : $scope.msgContent,
			"event" : "addMessage"
		}).success(function(data, status) {
			$scope.status = status;
			$scope.data = data;
			console.log(data);
			console.log(status);
		}).error(function(data, status) {
			console.log(data || "Request failed");
		});
		$scope.getMessages();
		$scope.msgContent = '';
	};

	$scope.getMessages = function() {
		$http.post($scope.url, {
			"senderId" : $scope.loggedInUserId,
			"receiverId" : $scope.selecteduser.userid,
			"event" : "getMessages"
		}).success(function(data, status) {
			$scope.status = status;
			$scope.messages = data;
			console.log(data);
			console.log(status);
		}).error(function(data, status) {
			console.log(data || "Request failed");
		});

	};

	$scope.getUsers = function() {
		$http.post($scope.url, {
			"event" : "getUsers"
		}).success(function(data, status) {
			$scope.status = status;
			$scope.users = data;
			console.log(data);
			console.log(status);
		}).error(function(data, status) {
			console.log(data || "Request failed");
		});

	};
	
	
	$scope.getUsers();
	
	$scope.userChanged = function(userid) {
		console.log($scope.users);
		console.log($scope.users.length);
		for(i=0; i<$scope.users.length; i++){
		console.log(userid);
			if($scope.users[i].userid==userid){
				$scope.selecteduser=$scope.users[i];
				$scope.users[i].active='active';
			}else{
				$scope.users[i].active='';
			}
		}
		console.log($scope.selecteduser);
		
		$scope.getMessages();
	}
	
	$scope.isUndefinedOrNull = function(val) {
		return angular.isUndefined(val) || val === null ;
	}
	
	$scope.getAlignment = function(actualSender, loggedInUser){
		if(actualSender === loggedInUser){
			return "right aligned";
		}else{
			return "left aligned";
		}
		
	}
	
});
