#Best Investments
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mannion007/best-investments-ddd-exercise/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mannion007/best-investments-ddd-exercise/?branch=master)
This codebase is being developed as a training exercise and introduction into Domain Driven Design and related topics. The task at hand is to develop an enterprise system for a fictional business, Best Investments.

An initial mock conversation between the developers and the domain experts of the domain the company is in went down as follows:

###The participants are:
Andy - Who is a Research Manager working at the Operations department of the company
Elli - CFO of the company, working with the Financial department
Maya - Sales manager, working at the Sales department
Vlad - Prospecting manager, works for an external 3rd party company to do outsourced work for BestInvestments ltd
Bruno - CTO of the company

Developer - So we are here today to talk about BestInvestmentsLtd, what you do and how you do it. Let’s start with something generic. How would you summarize what your company does in a few sentences?

Andy - Well, I think I could summarize it by saying we are a financial services company that facilitates consultations between investors and specialists, so that our clients can get a better understanding of market trends and conditions before making an investment.

Developer - wow, ok, there are a lot of nice word in there let me try to understand it better, so your product is essentially this meeting between the investors and the specialists?

Andy - Yes, sort of, this is not really a meeting, it happens over the phone, and it usually lasts for 1-2 hours  at a time, but there can be multiple consultations over a period of time on any given project.

Developer - Ah, I feel like we ran ahead a bit. So who are your clients?

Andy - Our clients are the financial investors, see they have a lot of money and looking for good investments every day, they are employed by big private equity firms, or hedge funds. They are good with money and numbers but in order to be able to decide whether they should invest in another company they generally need more information. Let me give you an example, let’s say there is a company that does ice fishing in scandinavia, their financial report looks amazing, but obviously the hedge fund manager is not an expert in ice fishing. In order to be sure about an investment the investor would have to do a lot of research about the subject, if you are to invest a 100 million into something you want to make sure it is a good investment, but he has dozens of different companies to do the same research for, so simply googling would be inefficient. 
What we do is that we find him people who have been working in that market for a long time and can give him a crash course on main trends and drivers, and what to expect on the market. 

Developer - And these are the people you call specialists, right?

Andy - Yes, so our clients are these investors who have signed up for our services, they can purchase packages or have a pay as you go payment model, but this is something Maya and Elli can tell you more about, by the time we researchers deal with them they are already signed up. 

Developer - Right, so are the specialists, the people they consult with your employees?

Andy - No, not in the traditional sense, they are people working in the industry our clients are looking at, they do not work for us, it is more like a case-by-case consultancy, like a contractor would. After each project ends, we bill the client for the consultations happened on that project , and we pay the specialist for his time 

Developer - And a project is… ?

Andy - Well a project is essentially one subject our investors are looking for advise on, we create a project and start adding specialists we consider suitable to it.

Developer - So where do you get the list of the specialists from?

Andy - That is a result of a cooperation between us and Vlad’s company. So essentially we  will use our channels to find people we consider suitable and put them on the list, that’s where Vlad’s team takes over and tries to get them to join up and become our specialists, because before we push them to clients we have to be sure they had signed our terms and conditions. Vlad will tell you more about how they manage that on their side.

Developer - ok, so what happens after they have signed up?

Andy - So before we talk to the client directly, we need to talk to their compliance officer to run through the people we are about to recommend to their analyst. They vet the list for any specialists they want to exclude from us even mentioning, for various reasons, mostly legal, but we never get told about what exactly, they just either approve or discard the specialist. Then we contact our client and tell them about potential specialists, talk about their background and expertise, and the client decides whether they want to talk or not. If they choose to go ahead, we schedule a consultation at a given time that suits them.

Developer - And if they don’t?

Andy - We keep looking for more specialists

Developer - So you schedule it when you are on the phone with the client?

Andy - Yes

Developer - How do you know when the specialist is available and for how long? 

Vlad - This is part of what we do when we sign them up, we ask for their availability. So we get potential specialists from Andy’s team and our job is to chase them up until we can get them to register, or declare that they are not interested. We might even give up if they case seems like a lost cause, if you know what I mean, that case we just mark them as not interested.

Andy - We usually assume the conversation will last for an hour, that’s very typical, but it can go on longer, that’s why after the consultation happened we ask the specialist to report how long it was, so we can accurately pay them. We work with 15 minute increments, and the specialist operate on an hourly rate.

Developer - Great, how many consultations take place on a given project?

Andy - Can be any number, until the client is satisfied, and of course one specialist can consult as many times as the client wants to hear from them.

Developer - And what happens when the client is finished with the project?

Andy - we close the project, in order to do that though, we need make sure all open consultations have either been discarded or confirmed.  Once we close it Elli and her team takes over and does the invoicing part.

Developer - ok, It is much clearer now, how do the projects start, who’s creating them?

Andy - That would be us, we speak to the client and get a grasp of what they are looking for. We give a project a name, a projected end date, which is more like a deadline. We will also get a reference to a project which is a 2 letter - 4 digit combination, which gets assigned randomly. This does not mean that the project has started, but more like a draft of a project, then a senior project manager will get notified of this and he will assign an actual project manager to the project to start it. After that happened, the project will officially start and specialists can be added to it from our database, or if we can’t find enough possible specialists we go on google/yahoo/linkedin and try to find people that might be suitable, and then the registration process kicks in, the one I talked about before with Vlad.
Obviously we can only recommend a specialist to our client when that specialist has already registered, because that’s the only way to set up a consultation.

Developer - Ok, I think I have a pretty good idea about project management now, is that what you call it?

Andy - Yes, you can say that.

Developer - What happens when the project ends?

Elli - That’s where invoicing starts. So what we do is that we have 2 invoicing models, clients can pay by purchasing packages, or use a pay-as-you-go model. They can even have both or multiple packages at the same time, that’s why once the project has ended we ask them which of their accounts they want to assign this project to.

Developer - Right, so what is the difference? And what are these packages?

Elli - So a package is a pre-purchased amount of consultation time. They can run for 6 or 12 months, and they can contain a number of hours.  For example a client can have a 6 months package containing 40 hours of consultation. If we assign a package to a closed project with 4 hours worth of consultation on it, it will only have 36 hours.

Developer - I see, so what happens if the package reaches 0 hours, or for example if the package only have 2 hours left but you want to assign it to a project with 4 hours of consultations? 

Elli - Nothing, you can still do that, but not endlessly. We don’t really care much if it runs over, the extra amount of time will always go to into their next package, or if they have not purchased their next package yet, it will be billed on an hourly basis separately, similar to pay-as-you-go.
This also applies to remaining amounts on the package, let’s say a client bought a package for 40 hours, but only used 35 by the time the package expires, in that case when they purchase another package they get 5 extra hours on top of their nominal hours in the package. However these extra hours cannot be used for pay-as-you-go, they only get added to new packages, if the client decides to buy a new one. So each package has nominal hours, that’s what the client purchased, and available hours, that’s what the total is after adding the difference.

Developer - How do you add this on?

Elii - When a package expires you cannot attach any more consultations to it, but it has to be closed down manually to decide what to do with the extra hours. If the client has consumed the package completely, it closes down automatically. So when there is anything left over, you can close it by hand, by assigning the remaining hours to another package that hasn’t started yet.

Developer - Ok, how do you reference these packages when you talk to your clients?

Elli - The packages have a reference, which consist of name, which is generally just a name or a grade, like gold, or silver, but it is really just a reference, it can be anything, plus year and month the when the package started, and finally the length.

Developer - Got it, and what about pay-as-you-go?

Elli - Well, pay-as-you-go has a fixed hourly rate, which is agreed upon with the client when they sign up to our services. The fundamental difference is that invoicing actually happens on each project with pay-as-you-go, and only once with packages at the end of the package.
That is basically it.

Developer - Great, so let’s hear a bit about how these clients get signed up for your services.

Maya - Sure, that’s our job. We reach out to potential clients and as Elli just described, we try to sell packages to them, or at least if they don’t want to commit to a package, get them to sign up to use our services with pay-as-you-go, to build confidence and trust.

Developer - ok, so what do you usually care about when you sign a client up?

Maya - Well, upon signing up we will have the name of the company, the main contact details, and what kind of package they bought and what their pay-as-you-go rate they will use.

Developer - Do you have any other responsibilities regarding clients?

Maya - Yes, essentially we are the client relationship managers as well, so we can suspend the service on the client’s request, that would put all their active projects on hold as well, until we resume operations for them. 
So we manage all this in a small client database system where we can add potential clients that we are working on with primary contact information and name.

Developer - Sounds good, Bruno do you have anything to add?

Bruno - Yes, of course we can’t let everyone just use our systems, we do need everyone to log in with their email address and password, and of course anything regarding projects, only research assistants can do, while adding new clients is the responsibility of the sales managers, similarly issuing an invoice will be something the financial coordinators will do.

Developer - I see, thanks for this initial conversation, let us meet again later on to run though the specifics again once we have something to show you.
